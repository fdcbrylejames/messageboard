<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Filesystem');
class UsersController extends AppController {
    public $helpers = array('Html', 'Form', 'Session');
    
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Flash');
    }
    

    public $components = array(
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'messages', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email', 'password' => 'password')
                )
            ),
            'authorize' => array('Controller')
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout', 'home', 'thank_you', 'register');
    }

    public function isAuthorized($user) {
        return true; 
    }

    
    public function register() {
        if ($this->Auth->user()) {
            return $this->redirect(['controller' => 'messages', 'action' => 'index']);
        }
        if ($this->request->is('post')) {
            $this->User->create();

            // Check if email already exists
            $existingUser = $this->User->find('first', array(
                'conditions' => array('User.email' => $this->request->data['User']['email'])
            ));

            if ($existingUser) {
                $this->Flash->error(__('This email is already registered.'));
            } else {
                if ($this->User->save($this->request->data)) {
                    return $this->redirect(['action' => 'thank_you']);
                }
                $this->Flash->error(__('Unable to register your account.'));
            }
        }
    }
    

    public function thank_you() {
        if ($this->Auth->user()) {
            return $this->redirect(['controller' => 'messages', 'action' => 'index']);
        }
    }

    public function home() {
        if ($this->Auth->user()) {
            return $this->redirect(['controller' => 'messages', 'action' => 'index']);
        }
    }

    
    public function login() {
        if ($this->Auth->user()) {
            return $this->redirect(['controller' => 'messages', 'action' => 'index']);
        }
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->User->id = $this->Auth->user('id');
                $this->User->saveField('last_login_time', date('Y-m-d H:i:s'));
                return $this->redirect(['controller' => 'messages', 'action' => 'index']);
            } else {
                
                $this->Flash->error(__('Invalid email or password, try again'));
            }
        }
    }
    
    

    public function logout() {
        // Clear CakePHP Auth session data
        $this->redirect($this->Auth->logout());
        
        // Optionally destroy other session data
        $this->request->getSession()->destroy();
        
        // Redirect to login page after logout
        return $this->redirect(['controller' => 'users', 'action' => 'login']);
    }
    
    
    public function user_details($id= NULL) {
        // Check if $id is provided and user exists
        if (!$id || !$this->User->exists($id)) {
            throw new NotFoundException(__('Invalid user'));
        }

        // Fetch user details based on $id
        $user = $this->User->findById($id);

        // Pass user data to the view
        $this->set('user', $user);
    }

    public function edit_profile($id = null) {
       
        $currentUserId = $this->Auth->user('id');
    
       
        if ($id != $currentUserId) {
            $this->Session->setFlash('You are not authorized to edit this profile.');
            return $this->redirect(array('action' => 'profile', $currentUserId));
        }
      
        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $id;
   
            if (!empty($this->request->data['User']['image']['name'])) {
                $file = $this->request->data['User']['image'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
    
                if (in_array($ext, $allowed_ext)) {
                    // Generate unique filename
                    $filename = 'user_' . $id . '_' . time() . '.' . $ext;
                    $uploadPath = WWW_ROOT . 'upload' . DS . $filename;
    
                    // Move uploaded file
                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        // Save filename to database
                        $this->request->data['User']['image'] = $filename;
                    } else {
                        $this->Session->setFlash('Failed to upload image. Please try again.');
                        return;
                    }
                } else {
                    $this->Session->setFlash('Invalid file format. Allowed formats: jpg, jpeg, png, gif');
                    return;
                }
            } else {
                unset($this->request->data['User']['image']);
            }
    
            
            if (!empty($this->request->data['User']['password']) && !empty($this->request->data['User']['confirm_password'])) {
                if ($this->request->data['User']['password'] === $this->request->data['User']['confirm_password']) {
                  
                    $this->request->data['User']['password'] = $this->request->data['User']['password'];
                } else {
                    
                    $this->Session->setFlash('New password and confirm password do not match. Please try again.');
                    return;
                }
            } elseif (empty($this->request->data['User']['password']) && empty($this->request->data['User']['confirm_password'])) {
                // Both password fields are empty, do nothing
                unset($this->request->data['User']['password']);
            } else {
                // Either password or confirm_password is empty
                $this->Session->setFlash('Please fill out both password fields.');
                return;
            }
    
            // Save user data
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('Your profile has been updated.');
                return $this->redirect(array('action' => 'profile', $id));
            } else {
                $this->Session->setFlash('Failed to update profile. Please correct errors.');
            }
        }
    
        // Load user data for the form
        if (!$this->request->data) {
            $this->request->data = $this->User->findById($id);
            // Ensure password fields are empty
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['confirm_password'] = '';
        }
    }
    

    
    
    
    
    
    


    public function profile($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid user'));
        }

        $user = $this->User->findById($id);
        if (!$user) {
            throw new NotFoundException(__('Invalid user'));
        }

        $this->set('user', $user);
    }
    public function searchUsers() {
        $this->autoRender = false; 
        $term = $this->request->query('term');
        $results = array();
    
        if (!empty($term)) {
            $users = $this->User->find('all', array(
                'conditions' => array(
                    'User.name LIKE' => '%' . $term . '%'
                ),
                'fields' => array('User.id', 'User.name')
            ));
    
            foreach ($users as $user) {
                $results[] = array(
                    'id' => $user['User']['id'],
                    'label' => $user['User']['name'],
                    'value' => $user['User']['name']
                );
            }
        }
    
        echo json_encode($results);
    }

   
    
}
?>

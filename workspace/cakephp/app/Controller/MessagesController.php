<?php
App::uses('AppController', 'Controller');


class MessagesController extends AppController {
    public $uses = array('Message', 'User');

    public $helpers = array('Html', 'Form', 'Js' => array('Jquery'));

    
   
 public function index() {
        $this->layout = 'ajax';
        $user_id = $this->Auth->user('id');
        $limit =10; // Number of messages to display initially
    
        // latest message IDs for each conversation, excluding soft-deleted messages
        $db = $this->Message->getDataSource();
        $subQuery = $db->buildStatement(
            array(
                'fields' => array('MAX(Message.id) as id'),
                'table' => $db->fullTableName($this->Message),
                'alias' => 'Message',
                'conditions' => array(
                    'OR' => array(
                        array('Message.user_id' => $user_id, 'Message.deleted_by_sender' => 0),
                        array('Message.recipient_id' => $user_id, 'Message.deleted_by_recipient' => 0)
                    )
                ),
                'group' => array(
                    'LEAST(Message.user_id, Message.recipient_id)',
                    'GREATEST(Message.user_id, Message.recipient_id)'
                )
            ),
            $this->Message
        );
    
        // found message IDs to fetch the message details with pagination
        $messages = $this->Message->find('all', array(
            'conditions' => array(
                'Message.id IN (' . $subQuery . ')'
            ),
            'fields' => array('Message.id', 'Message.body', 'Message.created', 'Message.user_id', 'Message.recipient_id'),
            'order' => 'Message.created DESC',
            'limit' => $limit,
            'contain' => array(
                'Sender' => array('fields' => array('id', 'image')),
                'Recipient' => array('fields' => array('id', 'image'))
            )
        ));
    
        // Add recipient_id to each message on view access
        foreach ($messages as &$message) {
            $message['Message']['other_user_id'] = ($message['Message']['user_id'] == $user_id) ? $message['Message']['recipient_id'] : $message['Message']['user_id'];
        }
    
        $this->set(compact('messages', 'user_id'));
    }
    
    public function loadMoreMessages($page = 2) {
        $this->layout = 'ajax';
        $user_id = $this->Auth->user('id');
        $limit = 10; // Number of messages to display per page
        $offset = ($page - 1) * $limit; // Calculate offset
    
   // latest message IDs for each conversation, excluding soft-deleted messages
        $db = $this->Message->getDataSource();
        $subQuery = $db->buildStatement(
            array(
                'fields' => array('MAX(Message.id) as id'),
                'table' => $db->fullTableName($this->Message),
                'alias' => 'Message',
                'conditions' => array(
                    'OR' => array(
                        array('Message.user_id' => $user_id, 'Message.deleted_by_sender' => 0),
                        array('Message.recipient_id' => $user_id, 'Message.deleted_by_recipient' => 0)
                    )
                ),
                'group' => array(
                    'LEAST(Message.user_id, Message.recipient_id)',
                    'GREATEST(Message.user_id, Message.recipient_id)'
                )
            ),
            $this->Message
        );
    
        // found message IDs to fetch the message details with pagination
        $messages = $this->Message->find('all', array(
            'conditions' => array(
                'Message.id IN (' . $subQuery . ')'
            ),
            'fields' => array('Message.id', 'Message.body', 'Message.created', 'Message.user_id', 'Message.recipient_id'),
            'order' => 'Message.created DESC',
            'limit' => $limit,
            'offset' => $offset,
            'contain' => array(
                'Sender' => array('fields' => array('id', 'image')),
                'Recipient' => array('fields' => array('id', 'image'))
            )
        ));
    
        // Add recipient_id to each message for easier access in the view
        foreach ($messages as &$message) {
            $message['Message']['other_user_id'] = ($message['Message']['user_id'] == $user_id) ? $message['Message']['recipient_id'] : $message['Message']['user_id'];
        }
    
        $this->set(compact('messages', 'user_id'));
        $this->render('/Messages/load_more_messages');
    }

      
    public function addNewMessage() {
        if ($this->request->is('post')) {
            $this->request->data['Message']['user_id'] = $this->Auth->user('id'); // Set the sender to the logged-in user
        // debug($this->request->data); 
        $this->Message->create();
        if ($this->Message->save($this->request->data)) {
            // $this->Session->setFlash(__('Your message has been sent.'));
            return $this->redirect(array('action' => 'index'));
        } else {
            // debug($this->Message->validationErrors); 
            $this->Session->setFlash(__('Unable to send your message.'));
        }
    }
    
    $users = $this->User->find('list');
    $this->set(compact('users'));
}

public function viewConversation($recipient_id) {
    $this->layout = 'ajax';
    $user_id = $this->Auth->user('id');
    $limit = 10; // Number of messages to display per page

    // the total number of messages
    $totalMessages = $this->Message->find('count', array(
        'conditions' => array(
            'OR' => array(
                array(
                    'Message.user_id' => $user_id,
                    'Message.recipient_id' => $recipient_id,
                    'Message.deleted_by_sender' => 0
                ),
                array(
                    'Message.user_id' => $recipient_id,
                    'Message.recipient_id' => $user_id,
                    'Message.deleted_by_recipient' => 0
                )
            )
        )
    ));

  // Fetch the initial set of messages
    $messages = $this->Message->find('all', array(
        'conditions' => array(
            'OR' => array(
                array(
                    'Message.user_id' => $user_id,
                    'Message.recipient_id' => $recipient_id,
                    'Message.deleted_by_sender' => 0
                ),
                array(
                    'Message.user_id' => $recipient_id,
                    'Message.recipient_id' => $user_id,
                    'Message.deleted_by_recipient' => 0
                )
            )
        ),
        'fields' => array('Message.id', 'Message.body', 'Message.created', 'Message.user_id', 'Message.recipient_id'),
        'order' => 'Message.created DESC',
        'limit' => $limit,
        'offset' => 0,
        'contain' => array(
            'Sender' => array('fields' => array('id', 'image')),
            'Recipient' => array('fields' => array('id', 'image'))
        )
    ));

    $data = [
        'messages' => $messages,
        'current_user_id' => $user_id,
        'recipient_id' => $recipient_id,
        'totalMessages' => $totalMessages,
        'limit' => $limit
    ];

    $this->set($data);
}
public function loadMoreConversationMessages($recipient_id, $page) {
    $this->layout = 'ajax';
    $current_user_id = $this->Auth->user('id'); // current user ID from authentication

    //$current_user_id is set 
    if (!$current_user_id) {
        //  current user ID is not available
        $this->Flash->error('Current user ID not found.');
        return $this->redirect(['controller' => 'users', 'action' => 'login']); // Example redirect
    }

    $limit = 10; // Load 10 messages at a time
    $offset = ($page - 1) * $limit;

    $messages = $this->Message->find('all', array(
        'conditions' => array(
            'OR' => array(
                array(
                    'Message.user_id' => $current_user_id,
                    'Message.recipient_id' => $recipient_id,
                    'Message.deleted_by_sender' => 0
                ),
                array(
                    'Message.user_id' => $recipient_id,
                    'Message.recipient_id' => $current_user_id,
                    'Message.deleted_by_recipient' => 0
                )
            )
        ),
        'fields' => array('Message.id', 'Message.body', 'Message.created', 'Message.user_id', 'Message.recipient_id'),
        'order' => 'Message.created DESC',
        'limit' => $limit,
        'offset' => $offset,
        'contain' => array(
            'Sender' => array('fields' => array('id', 'image')),
            'Recipient' => array('fields' => array('id', 'image'))
        )
    ));

    $this->set([
        'messages' => $messages,
        'current_user_id' => $current_user_id,
        'recipient_id' => $recipient_id
    ]);

    //  if there are more messages to load
    $totalMessages = $this->Message->find('count', array(
        'conditions' => array(
            'OR' => array(
                array(
                    'Message.user_id' => $current_user_id,
                    'Message.recipient_id' => $recipient_id,
                    'Message.deleted_by_sender' => 0
                ),
                array(
                    'Message.user_id' => $recipient_id,
                    'Message.recipient_id' => $current_user_id,
                    'Message.deleted_by_recipient' => 0
                )
            )
        )
    ));

    //  if there are more messages to load
    $loadMore = count($messages) > 0 && ($offset + count($messages)) < $totalMessages;

    $this->set(compact('loadMore'));

    // Render the load more conversation messages view
    $this->render('load_more_conversation_messages');
}




public function deleteConversation() {
    $this->autoRender = false; 

    if ($this->request->is('post')) {
        $currentUserId = $this->request->data('current_user_id');
        $recipientId = $this->request->data('recipient_id');

        // if the current user is the sender or recipient
        $isSender = $this->Message->find('count', array(
            'conditions' => array('Message.user_id' => $currentUserId, 'Message.recipient_id' => $recipientId)
        )) > 0;

        $isRecipient = $this->Message->find('count', array(
            'conditions' => array('Message.user_id' => $recipientId, 'Message.recipient_id' => $currentUserId)
        )) > 0;

        // Update the deleted flags
        if ($isSender) {
            $this->Message->updateAll(
                array('Message.deleted_by_sender' => 1),
                array('Message.user_id' => $currentUserId, 'Message.recipient_id' => $recipientId)
            );
        }

        if ($isRecipient) {
            $this->Message->updateAll(
                array('Message.deleted_by_recipient' => 1),
                array('Message.user_id' => $recipientId, 'Message.recipient_id' => $currentUserId)
            );
        }

        echo json_encode(array('success' => true));
    }
}

public function deleteMessage() {
    $this->autoRender = false;
    if ($this->request->is('post')) {
        $messageId = $this->request->data('message_id');
        $currentUserId = $this->request->data('current_user_id');

        $message = $this->Message->findById($messageId);

        if ($message) {
            if ($message['Message']['user_id'] == $currentUserId) {
                $this->Message->id = $messageId;
                if ($this->Message->saveField('deleted_by_sender', 1)) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to delete message']);
                }
            } elseif ($message['Message']['recipient_id'] == $currentUserId) {
                $this->Message->id = $messageId;
                if ($this->Message->saveField('deleted_by_recipient', 1)) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to delete message']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Not authorized to delete this message']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Message not found']);
        }
    }
}


    
    public function view($message_id) {
        $this->layout = 'ajax';
        $message = $this->Message->find('first', array(
            'conditions' => array('Message.id' => $message_id),
            'conditions' => array('Message.created' => 'DESC'),
            'contain' => array(
                'Sender' => array('fields' => array('id', 'image')),
                'Recipient' => array('fields' => array('id', 'image'))
            )
        ));
        
        $this->set('message', $message);
       
    }


public function sendMessage() {
    $this->autoRender = false; 
    $this->response->type('json'); 

    if ($this->request->is('ajax')) {
        $messageBody = $this->request->data('message_body');
        $currentUserId = $this->request->data('current_user_id');
        $recipientId = $this->request->data('recipient_id');

        if (empty($messageBody) || empty($currentUserId) || empty($recipientId)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        $this->loadModel('Message'); // Message model is loaded
        $this->Message->create();
        $data = array(
            'body' => $messageBody, 
            'user_id' => $currentUserId,
            'recipient_id' => $recipientId,
            'created' => date('Y-m-d H:i:s')
        );

        if ($this->Message->save($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
        } else {
            $errors = $this->Message->validationErrors;
            echo json_encode(['status' => 'error', 'message' => 'Failed to send message', 'errors' => $errors]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request type']);
    }
}



}

?>
<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller

{
    public $helpers = array('Html', 'Form', 'Flash');
    public $ext = '.php';
    // public function initialize(): void
    // {
    //     parent::initialize();
    //     $this->loadComponent('RequestHandler');
    //     $this->loadComponent('Flash');
    //     $this->loadComponent('Authentication.Authentication');
    // }

    // public function beforeFilter(EventInterface $event)
    // {
    //     $this->set('currentUser', $this->Authentication->getIdentity());
    // }

//     public function initialize(): void
// {
//     parent::initialize();
//     $this->loadComponent('RequestHandler');
//     $this->loadComponent('Flash');
//     $this->loadComponent('Authentication.Authentication', [
//         'logoutRedirect' => [
//             'controller' => 'Users',
//             'action' => 'login',
//         ]
//     ]);
// }

public $components = array(
    'Session',
    'Auth' => array(
        'authenticate' => array(
            'Form' => array(
                'fields' => array('username' => 'email')
            )
        ),
        'loginRedirect' => array('controller' => 'Messages', 'action' => 'index'),
        'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
        'authError' => 'You must be logged in to view this page.',
        'loginError' => 'Invalid email or password, try again.'
    )
);

public function beforeFilter() {
    $this->Auth->allow('register', 'login');
}
// // In PagesController.php
// public function home() {
    
// }


}
?>
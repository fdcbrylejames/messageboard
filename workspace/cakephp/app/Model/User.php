<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class User extends AppModel {

    public $hasMany = array(
        'Message' => array(
            'className' => 'Message',
            'foreignKey' => 'user_id'
        ),
        'ReceivedMessage' => array(
            'className' => 'Message',
            'foreignKey' => 'recipient_id'
        )
    );


    public $validate = array(
        'email' => array(
            'rule' => 'isUnique',
            'message' => 'This email is already taken'
        ),
        'email' => array(
        'required' => array(
            'rule' => 'notBlank',
            'message' => 'Email is required'
        ),
        'email' => array(
            'rule' => 'email',
            'message' => 'Please provide a valid email address'
        ),
        'unique' => array(
            'rule' => 'isUnique',
            'message' => 'This email is already registered'
        )
    ),
        // 'name' => array(
        //     'required' => array(
        //         'rule' => 'notBlank',
        //         'message' => 'Name is required'
        //     ),
        //     'length' => array(
        //         'rule' => array('lengthBetween', 5, 20),
        //         'message' => 'Name should be between 5 to 20 characters'
        //     )
        // ),
        'name' => array(
            'required' => array(
                'rule' => array('minLength', 5),
                'message' => 'Name is required and should be between 5-20 characters',
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 20),
                'message' => 'Name is required and should be between 5-20 characters',
            ),
        ),
        // 'birthdate' => array(
        //     'required' => array(
        //         'rule' => 'notBlank',
        //         'message' => 'Birthdate is required',
        //     ),
        // ),
        // 'gender' => array(
        //     'required' => array(
        //         'rule' => 'notBlank',
        //         'message' => 'Gender is required',
        //     ),
        // ),
        // 'hobby' => array(
        //     'required' => array(
        //         'rule' => 'notBlank',
        //         'message' => 'Hobby is required',
        //     ),
        // ),
        'password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Password is required'
            ),
            'comparePasswords' => array(
                'rule' => array('validatePasswords'),
                'message' => 'Passwords do not match.'
            )
        ),
        'confirm_password' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Confirm Password is required'
            )
        )
    );

    public function validatePasswords($data) {
        return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
    }
    
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        if (isset($this->data[$this->alias]['last_login_time'])) {
            $this->data[$this->alias]['last_login_time'] = date('Y-m-d H:i:s');
        }
        return true;
}

}
?>

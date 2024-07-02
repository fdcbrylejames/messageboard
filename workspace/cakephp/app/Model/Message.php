<?php
App::uses('AppModel', 'Model');

class Message extends AppModel {
    public $name = 'Message';
    public $belongsTo = array(
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Recipient' => array(
            'className' => 'User',
            'foreignKey' => 'recipient_id'
        )
        
        
    );

    public $actsAs = array('Containable');

    public $validate = array(
        // 'subject' => array(
        //     'required' => array(
        //         'rule' => 'notBlank',
        //         'message' => 'Subject is required'
        //     )
        // ),
        'body' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Body is required'
            )
        )
        // 'recipient_id' => array(
        //     'rule' => 'notBlank',
        //     'message' => 'Recipient is required'
        // )
    );
    
}
?>

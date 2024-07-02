
<h2>Login</h2>
<?php
    echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'login')));
    echo $this->Form->input('email', array('type' => 'email', 'label' => 'Email'));
    echo $this->Form->input('password', array('type' => 'password', 'label' => 'Password'));
    echo $this->Form->end('Login');
?>

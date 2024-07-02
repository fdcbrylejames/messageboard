<!DOCTYPE html>
<html>
<head>
    <title><?php echo $this->fetch('title'); ?></title>
    <!-- Include your CSS and other meta tags here -->
</head>
<body>

<!-- <div id="header">
    <div class="menu">
        <ul>
            <li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></li>
            <li><?php echo $this->Html->link('Edit Profile', array('controller' => 'users', 'action' => 'edit_profile')); ?></li>
            <li><?php echo $this->Html->link('Message List', array('controller' => 'messages', 'action' => 'index')); ?></li>
        </ul>
    </div>
</div> -->

<?php echo $this->Flash->render(); ?>
<?php echo $this->fetch('content'); ?>

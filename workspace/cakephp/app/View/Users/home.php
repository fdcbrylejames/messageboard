<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
</head>
<body>
    <h2>Welcome to Our Website!</h2>
    <p>If you change your mind, you can <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'register')); ?>">register here</a>.</p>
</body>
</html>

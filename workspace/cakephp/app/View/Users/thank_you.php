<!DOCTYPE html>
<html>
<head>
    <title>Thank You</title>
</head>
<body>
    <h2>Thank You for Registering!</h2>
    <p>Your registration was successful.</p>
    <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>">Go to Login</a>
</body>
</html>

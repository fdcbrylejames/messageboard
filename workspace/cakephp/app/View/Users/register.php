<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script>
        function validateForm() {
            var name = document.forms["registerForm"]["data[User][name]"].value;
            var email = document.forms["registerForm"]["data[User][email]"].value;
            var password = document.forms["registerForm"]["data[User][password]"].value;
            var confirmPassword = document.forms["registerForm"]["data[User][confirm_password]"].value;
            
            if (name.length < 5 || name.length > 20) {
                alert("Name should be between 5 to 20 characters.");
                return false;
            }
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            if (password == "") {
                alert("Password is required.");
                return false;
            }
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h2>Register</h2>
    <?php
    // Assuming $defaultImageFilename contains the path to your default image
    $defaultImageFilename = 'avatar.png'; // Relative path from webroot

    echo $this->Form->create('User', array('onsubmit' => 'return validateForm()', 'name' => 'registerForm'));
    echo $this->Form->hidden('image', ['value' => $defaultImageFilename]);
    echo $this->Form->input('name');
    echo $this->Form->input('email');
    echo $this->Form->input('password');
    echo $this->Form->input('confirm_password', array('type' => 'password'));
    echo $this->Form->end('Register');
    ?>
    <p>Don't want to register? <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'home')); ?>">Go to Home Page</a></p>
</body>
</html>

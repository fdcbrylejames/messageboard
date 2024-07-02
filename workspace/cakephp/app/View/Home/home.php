<!-- app/View/Pages/home.ctp -->

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h2>Welcome to Our Site</h2>
    <p>This is the home page of our website.</p>
    <p><a href="<?php echo $this->Url->build(['controller' => 'users', 'action' => 'register']); ?>">Register</a></p>
</body>
</html>

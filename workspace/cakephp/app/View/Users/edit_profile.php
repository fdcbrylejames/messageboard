<?php
echo $this->Html->script('https://code.jquery.com/jquery-3.6.0.min.js');
echo $this->Html->script('https://code.jquery.com/ui/1.12.1/jquery-ui.min.js');
echo $this->Html->css('https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
echo $this->Html->scriptBlock('
    $(function() {
        $("#UserBirthdate").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
');
?>

<style>
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        font-weight: bold;
    }
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"],
    .form-group input[type="file"],
    .form-group .radio {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
    }
    #image-preview img {
        display: block;
        margin-top: 10px;
    }
</style>

<h2>Edit Profile</h2>

<?php echo $this->Form->create('User', array('type' => 'file')); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<?php if ($this->Session->check('Message.flash')): ?>
<div class="message"><?php echo $this->Session->flash(); ?></div>
<?php endif; ?>

<div class="form-group">
    <?php
    echo $this->Form->input('image', array('label' => 'Profile Picture', 'type' => 'file', 'div' => 'form-group'));
    ?>
    <div id="image-preview"></div>
</div>
<div class="form-group">
    <?php
    echo $this->Form->input('name', array('label' => 'Name', 'div' => 'form-group'));
    echo $this->Form->input('email', array('label' => 'Email', 'div' => 'form-group'));
    echo $this->Form->input('birthdate', array('label' => 'Birthdate', 'div' => 'form-group'));
    echo $this->Form->input('gender', array('label' => 'Gender', 'type' => 'radio', 'options' => array('Male' => 'Male', 'Female' => 'Female'), 'div' => 'form-group'));
    echo $this->Form->input('hobby', array('label' => 'Hobby', 'div' => 'form-group'));
    ?>
</div>
<div class="form-group">
    <?php
    echo $this->Form->input('password', array('label' => 'New Password', 'type' => 'password', 'div' => 'form-group'));
    echo $this->Form->input('confirm_password', array('label' => 'Confirm New Password', 'type' => 'password', 'div' => 'form-group'));
    ?>
</div>

<?php echo $this->Form->end('Update'); ?>

<script>
    // Image preview script
    document.getElementById("UserImage").addEventListener("change", function() {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("image-preview").innerHTML = '<img src="' + e.target.result + '" width="150" height="150"/>';
        };
        reader.readAsDataURL(this.files[0]);
    });
</script>

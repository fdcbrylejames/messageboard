
<h2><?php echo h($user['User']['name']); ?>'s Profile</h2>

<?php if (!empty($user['User']['image'])): ?>
    <div class="profile-image">
        <img src="<?php echo $this->Html->url('/upload/' . $user['User']['image']); ?>" alt="Profile Picture" width="150" height="150">
    </div>
<?php endif; ?>

<p><strong>Name:</strong> <?php echo h($user['User']['name']); ?></p>
<p><strong>Email:</strong> <?php echo h($user['User']['email']); ?></p>
<p><strong>Birthdate:</strong> <?php echo h($user['User']['birthdate']); ?></p>
<p><strong>Age:</strong> <?php echo calculateAge($user['User']['birthdate']); ?></p>
<p><strong>Created Date:</strong> <?php echo h($user['User']['created']); ?></p>
<p><strong>Last Login:</strong> <?php echo h($user['User']['last_login_time']); ?></p>
<p><strong>Hobby:</strong> <?php echo h($user['User']['hobby']); ?></p>

<?php
// Function to calculate age based on birthdate
function calculateAge($birthdate) {
    $dob = new DateTime($birthdate);
    $now = new DateTime();
    $age = $now->diff($dob)->y;
    return $age;
}
?>

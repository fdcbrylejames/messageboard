<h1>List of Users</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Last Login Time</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['User']['id']; ?></td>
        <td><?php echo $user['User']['name']; ?></td>
        <td><?php echo $user['User']['email']; ?></td>
        <td><?php echo $user['User']['last_login_time']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>

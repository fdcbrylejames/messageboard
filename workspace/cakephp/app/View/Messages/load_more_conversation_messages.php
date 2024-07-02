<style>
    #viewConversation {
        margin: 0;
        padding: 0;
        list-style-type: none;
    }

    .message {
        clear: both;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .message-content {
        padding: 10px;
        border-radius: 10px;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    .bg-blue {
        background-color: #d6eaf8; /* Mild blue color for sender */
        color: #333; /* Dark text color */
    }

    .bg-yellow {
        background-color: #fdf2d9; /* Mild yellow color for recipient */
        color: #333; /* Dark text color */
    }

    .default-image-placeholder {
        width: 50px;
        height: 50px;
        background-color: #ccc;
        text-align: center;
        line-height: 50px;
    }

    .message-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-bottom: 5px;
    }

    .right {
        float: right;
        margin-left: 10px;
    }

    .left {
        float: left;
        margin-right: 10px;
    }
</style>
<?php
//  $current_user_id is defined
if (!isset($current_user_id)) {
    $current_user_id = ''; // default value 
}
?>

<div id="viewConversation">
    <?php foreach ($messages as $message): ?>
        <div class="message <?php echo $message['Sender']['id'] == $current_user_id ? "sender" : "recipient" ?>" id="message-<?php echo $message['Message']['id']; ?>">
            <?php if ($message['Sender']['id'] == $current_user_id): ?>
                <?php if (!empty($message['Sender']['image'])): ?>
                    <img src="<?php echo Router::url('/upload/' . $message['Sender']['image']); ?>" alt="Sender Image" class="message-image right">
                <?php else: ?>
                    <div class="default-image-placeholder right">No Image</div>
                <?php endif; ?>
            <?php else: ?>
                <?php if (!empty($message['Sender']['image'])): ?>
                    <img src="<?php echo Router::url('/upload/' . $message['Sender']['image']); ?>" alt="Recipient Image" class="message-image left">
                <?php else: ?>
                    <div class="default-image-placeholder left">No Image</div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="message-content <?php echo $message['Sender']['id'] == $current_user_id ? "text-right bg-blue" : "text-left bg-yellow" ?>">
                <p><?php echo h($message['Message']['body']); ?></p>
                <p class="message-time"><?php echo h($message['Message']['created']); ?></p>
                <button class="delete-message" data-message-id="<?php echo $message['Message']['id']; ?>">Delete</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php foreach ($messages as $message): ?>
    <?php 
        $conversationId = $message['Sender']['id'] == $user_id ? $message['Recipient']['id'] : $message['Sender']['id'];
    ?>
    <div class="message <?php echo $message['Sender']['id'] == $user_id ? "sender" : "recipient" ?>" id="conversation-<?php echo $conversationId; ?>">
        <?php if (!empty($message['Sender']['image'])): ?>
            <img src="<?php echo Router::url('/upload/' . $message['Sender']['image']); ?>" alt="Sender Image" width="50" height="50">
        <?php elseif (!empty($message['Recipient']['image'])): ?>
            <img src="<?php echo Router::url('/upload/' . $message['Recipient']['image']); ?>" alt="Recipient Image" width="50" height="50">
        <?php else: ?>
            <div class="default-image-placeholder">No Image</div>
        <?php endif; ?>

        <div class="message-content">
            <p><?php echo $message['Message']['body']; ?></p>
            <p class="message-time"><?php echo $message['Message']['created']; ?></p>
           
            <?php 
            if ($user_id == $message['Message']['recipient_id']) { 
            ?>
                <a href="<?php echo Router::url(array('controller' => 'messages', 'action' => 'viewConversation', $message['Sender']['id'])); ?>" class="expand-conversation" data-message-id="<?php echo $message['Message']['id']; ?>">Expand</a>
                <button class="delete-conversation" data-recipient-id="<?php echo $message['Sender']['id']; ?>">Delete Conversation</button>
            <?php 
            } else { 
            ?>
                <a href="<?php echo Router::url(array('controller' => 'messages', 'action' => 'viewConversation', $message['Recipient']['id'])); ?>" class="expand-conversation" data-message-id="<?php echo $message['Message']['id']; ?>">Expand</a>
                <button class="delete-conversation" data-recipient-id="<?php echo $message['Recipient']['id']; ?>">Delete Conversation</button>
            <?php 
            } 
            ?>
        </div>
    </div>
<?php endforeach; ?>

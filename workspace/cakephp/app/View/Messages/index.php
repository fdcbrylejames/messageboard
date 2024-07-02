<style>
    #message-list {
        margin: 0;
        padding: 0;
        list-style-type: none;
    }

    .message {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .message img {
        border-radius: 50%;
        margin-right: 10px;
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

    /* Specific styling for sender and recipient */
    .message.sender .message-content {
        background-color: #e1f5fe; /* Light blue background for sender */
        padding: 10px;
        border-radius: 5px;
    }

    .message.recipient .message-content {
        background-color: #fff9c4; /* Light yellow background for recipient */
        padding: 10px;
        border-radius: 5px;
    }
    .new-message-button {
        text-align: right; /* Aligns the button to the right */
        margin-bottom: 10px; /* Adds some space below the button */
    }

    .new-message-button .custom-button {
        border: 2px solid; /* Adds a border to the button */
    }

</style>

<header>
    <h1>Message List</h1>
</header>

<div class="new-message-button">
    <a href="<?php echo Router::url(array('controller' => 'messages', 'action' => 'addNewMessage')); ?>" class="btn btn-primary custom-button">New Message</a>
</div>

<div id="message-list">
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
                <!-- Link to view the full conversation -->
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
</div>

<div id="load-more-container" class="text-center">
    <button id="load-more" class="btn btn-primary" data-page="1">See More</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Event delegation for delete conversation
        $('#message-list').on('click', '.delete-conversation', function() {
            var recipientId = $(this).data('recipient-id');
            var conversationId = '#conversation-' + recipientId;

            if (confirm('Are you sure you want to delete this conversation?')) {
                $.ajax({
                    url: '<?php echo Router::url(array("controller" => "messages", "action" => "deleteConversation")); ?>',
                    type: 'POST',
                    data: {
                        recipient_id: recipientId,
                        current_user_id: <?php echo $user_id; ?>
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.success) {
                            $(conversationId).remove(); // Remove the conversation from the DOM
                        } else {
                            alert('Error deleting conversation.');
                        }
                    },
                    error: function() {
                        alert('Error deleting conversation.');
                    }
                });
            }
        });

        // Load more messages
        function checkForMoreMessages(page) {
        $.ajax({
            url: '<?php echo Router::url(array("controller" => "messages", "action" => "loadMoreMessages")); ?>/' + page,
            type: 'GET',
            success: function(response) {
                if (response) {
                    $('#load-more').show(); // Show the button if there are more messages
                } else {
                    $('#load-more').remove(); // Remove the button 
                }
            },
            error: function() {
                alert('Error checking for more messages.');
            }
        });
    }

    // Initial check for more messages when the page loads
    var initialPage = 1;
    checkForMoreMessages(initialPage);

    // Click event for loading more messages
    $('#load-more').on('click', function() {
        var button = $(this);
        var page = button.data('page');
        page++; // Increment page number

        $.ajax({
            url: '<?php echo Router::url(array("controller" => "messages", "action" => "loadMoreMessages")); ?>/' + page,
            type: 'GET',
            success: function(response) {
                if (response) {
                    $('#message-list').append(response);
                    button.data('page', page); // Update page number on button
                    
                } else {
                    button.remove(); // Remove button if no more messages
                }
            },
            error: function() {
                alert('Error loading more messages.');
            }
        });
    });
    });
</script>
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

<form id="reply-form">
    <textarea id="message-body" name="message_body" placeholder="Type your message"></textarea>
    <button type="submit">Send</button>
</form>

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
                    <img src="<?php echo Router::url('/upload/' . $message['Sender']['image']); ?>" alt="Sender Image" class="message-image left">
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

<?php if ($totalMessages > $limit): ?>
    <button id="load-more" data-page="1" data-recipient-id="<?php echo $recipient_id; ?>">See More</button>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var currentUserId = <?php echo json_encode($current_user_id); ?>;
    var recipientId = <?php echo json_encode($recipient_id); ?>;
    var limit = <?php echo json_encode($limit); ?>;

    $('#reply-form').submit(function(event) {
        event.preventDefault(); // Prevent normal form submission

        var messageBody = $('#message-body').val().trim();
        if (messageBody === '') {
            alert('Message body cannot be empty');
            return;
        }

        // AJAX call to submit the message
        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'sendMessage')); ?>',
            type: 'POST',
            data: {
                message_body: messageBody,
                current_user_id: currentUserId,
                recipient_id: recipientId
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    $('#message-body').val(''); // Clear the textarea

                    // Prepend the new message to the conversation view
                    var newMessageHtml = '<div class="message sender" id="message-' + result.message_id + '">' +
                        '<div class="message-content text-right bg-blue">' +
                        '<p>' + $('<div>').text(messageBody).html() + '</p>' + // Escape HTML
                        '<p class="message-time">Just now</p>' +
                        '<button class="delete-message" data-message-id="' + result.message_id + '">Delete</button>' +
                        '</div></div>';

                    $('#viewConversation').prepend(newMessageHtml); // Prepend the new message
                } else {
                    alert('Failed to send message: ' + result.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Failed to send message. Please try again.');
            }
        });
    });

    // AJAX delete function
    $(document).on('click', '.delete-message', function() {
        var messageId = $(this).data('message-id');

        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'deleteMessage')); ?>',
            type: 'POST',
            data: {
                message_id: messageId,
                current_user_id: currentUserId
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    $('#message-' + messageId).remove(); // Remove the message from the view
                } else {
                    alert('Failed to delete message: ' + result.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('Failed to delete message. Please try again.');
            }
        });
    });

    // Load more messages
    $('#load-more').on('click', function() {
        var button = $(this);
        var page = button.data('page');
        page++;

        $.ajax({
            url: '<?php echo Router::url(array("controller" => "messages", "action" => "loadMoreConversationMessages")); ?>/' + recipientId + '/' + page,
            type: 'GET',
            success: function(response) {
                if (response.trim() !== '') {
                    $('#viewConversation').append(response);
                    button.data('page', page); // Update page number on button

                    // Check if there are less than `limit` messages returned
                    var loadedMessages = $('#viewConversation').find('.message').length; // Count all messages in view

                    if (loadedMessages >= <?php echo $totalMessages; ?>) {
                        button.remove(); // Remove button if all messages are loaded
                    }
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
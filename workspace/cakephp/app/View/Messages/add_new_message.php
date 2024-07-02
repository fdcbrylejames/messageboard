<h1>New Message</h1>
<?php
echo $this->Form->create('Message', array('id' => 'messageForm'));
?>
<div class="form-group">
    <?php
    //echo $this->Form->label('recipient', 'Recipient');
    echo $this->Form->input('recipient', array('type' => 'text', 'id' => 'recipient-autocomplete', 'autocomplete' => 'off'));
    echo $this->Form->input('recipient_id', array('type' => 'hidden', 'id' => 'recipient-id'));
    ?>
</div>
<div class="form-group">
    <?php
    echo $this->Form->label('body', 'Message');
    echo $this->Form->textarea('body');
    ?>
</div>
<?php
echo $this->Form->end('Send Message');
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script>
$(document).ready(function() {
    $('#recipient-autocomplete').autocomplete({
        source: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'searchUsers')); ?>',
        minLength: 2,
        select: function(event, ui) {
            $('#recipient-autocomplete').val(ui.item.label);
            $('#recipient-id').val(ui.item.id);
            return false;
        }
    });
});
</script>

<?php echo $this->Html->script('elements'.DS.'private_message',array('inline' => false)); ?>

<div id="send_private_message" title="Send private message"	class="hidden">


	<p>
		You have <span id="privateMessageCharacters">1000</span> characters
		left.
	</p>	
	
	<?php echo $form->create('PrivateMessage', array('url' => '#','inputDefaults' => array('div' => true), 'id' => 'PrivateMessageForm')); ?>
	
	<label for="PrivateMessageTo">To</label>
	<p id="PrivateMessageTo"></p>
	
	<?php echo $form->hidden('UserPrivateMessage.receiver_id', array('value' => 0)); ?>
	<?php echo $form->hidden('PrivateMessage.parent_id', array('value' => 0)); ?>
	<?php echo $form->input('title', array('type' => 'text',
											'label' => 'Title')
	); ?>
	<?php echo $form->input('message', array('type' => 'textarea',
											'rows' => 6,
											'cols' => 40,
											'label' => 'Message',
											'div' => array('class' => 'margin-top'))
	); ?>	
	
	<?php echo $form->end(); ?>
</div>

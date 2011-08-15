<?php echo $this->Html->script('elements'.DS.'private_message',array('inline' => false)); ?>

<div id="compose_private_message" title="Send private message"	class="hidden">


	<p>
		You have <span id="privateMessageComposeCharacters">1000</span> characters
		left.
	</p>	
	
	<?php echo $form->create('PrivateMessage', array('url' => '#','inputDefaults' => array('div' => true), 'id' => 'PrivateMessageComposeForm')); ?>
	<div class="privateMessageReceivers">
		
	</div>
	<p id="PrivateMessageComposeTo">
		<?php echo $form->input('UserPrivateMessage.Receiver.username', array('type' => 'text',
												'label' => 'Add Receiver')
		); ?>
	</p>
	
	<?php echo $form->input('title', array('type' => 'text',
											'label' => 'Title',
											'id' => 'PrivateMessageComposeTitle')
	); ?>
	<?php echo $form->input('message', array('type' => 'textarea',
											'rows' => 6,
											'cols' => 40,
											'label' => 'Message',
											'id' => 'PrivateMessageComposeMessage',
											'div' => array('class' => 'margin-top'))
	); ?>	
	
	<?php echo $form->end(); ?>
</div>

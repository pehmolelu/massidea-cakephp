<?php echo $this->element('global'.DS.'private_message');
?>
<h2>Inbox</h2>
<?php echo $this->Form->create(); ?>
<div id="accordion">
	<?php foreach ($messages as $message): ?>
    
		<div class="message-header">
			<div class="message-from">
				<a href="#" class="message-from "><?php echo $message['User']['username']; ?>:</a>
			</div>
			<div class="message-title">
				<a href="#" >
				<?php if(isset($message['PrivateMessage']['title'])) {
					echo $message['PrivateMessage']['title'];
				} else {
					echo "No Title";
				}
				
				?></a>
			</div>
			<div>
				<?php
					echo $this->Form->label('delete','No',array('class'=>'confirm-delete right blue delete-no'));
					echo $this->Form->label('delete',' | ',array('class'=>'confirm-delete right grey'));
					echo $this->Form->label('delete','Yes',array('class'=>'confirm-delete right blue delete-yes'));
					echo $this->Form->label('delete','Delete:',array('class'=>'confirm-delete right'));
					echo $this->Html->image('icon_red_cross.png',array('class'=>'right title-icon delete'));
					echo $this->Html->image('icon_reply.png',array('class'=>'right title-icon send'));
				?>
				<input type="hidden" value="<?php echo $message['User']['id'] ?>" class="send-message-id" />
				<input type="hidden" value="<?php echo $message['User']['username'] ?>" class="send-message-name" />
				<div class="clear-right"></div>
			</div>

		</div>

	<div class="private-message">
				<?php echo $message['PrivateMessage']['message']; ?>
			
			</div>
	
	<?endforeach;?>
</div>
<?php echo $this->Form->end(); ?>
<div id="create_message_tag" title="Create new tag"	class="hidden">
	<?php echo $form->create('PrivateMessageTags',
				 array('url' => '#',
				 		'id' => 'PrivateMessageTagsAddForm',
						'inputDefaults' => array('div' => true))); ?>
	<?php echo $form->input('title', array('type' => 'text',
											'label' => 'Title ')
	); ?>
	<?php echo $form->end(); ?>
</div>
<div id="create_message_tag" title="Create new tag"	class="hidden">
	<?php echo $form->create('PrivateMessageTag',
				 array('url' => '#',
				 		'id' => 'PrivateMessageTagsAddForm',
						'inputDefaults' => array('div' => true))); ?>

	<?php echo $form->input('name', array('type' => 'text',
											'label' => 'Title ')
	); ?>
	<div id="colorpicker"></div>
	<?php echo $form->input('color', array('type' => 'text',
											'label' => 'Color ',
											'value' => '#ccc')
	); ?>
	
	<?php echo $form->end(); ?>

		

</div>
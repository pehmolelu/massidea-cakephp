<?php
class OutboxTag extends AppModel {
	var $name = 'OutboxTag';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'PrivateMessage' => array(
			'className' => 'PrivateMessage',
			'foreignKey' => 'private_message_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'PrivateMessageTag' => array(
			'className' => 'PrivateMessageTag',
			'foreignKey' => 'private_message_tag_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>
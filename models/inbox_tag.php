<?php
class InboxTag extends AppModel {
	var $name = 'InboxTag';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'UserPrivateMessage' => array(
			'className' => 'UserPrivateMessage',
			'foreignKey' => 'user_private_message_id',
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
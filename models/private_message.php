<?php
class PrivateMessage extends AppModel {
	var $name = 'PrivateMessage';
	var $displayField = 'sender';
	var $validate = array(
		'message' => array(
			'maxlength' => array(
				'rule' => array('maxlength',1000),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	var $belongsTo = array(
		'UserReceiver' => array(
			'className' => 'User',
			'foreignKey' => 'receiver'
		),
		'UserSender' => array(
			'className' => 'User',
			'foreignKey' => 'sender'
		)
	);
	
}
?>
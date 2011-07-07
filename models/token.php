<?php
class Token extends AppModel {
	var $name = 'Token';
	/*
	var $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	*/
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	function getActivationToken($code = null) {
		$return = $this->find(
			'first', 
			array('conditions' => array('Token.value' => (string)$code))
		);
		return $return;
	}
	
	function clearActivationToken($id = null) {
		return $this->delete($id);
	}
	
	function hasPendingActivation($userId = null) {
		$return = $this->find(
			'count', array(
				'conditions' => array(
					'Token.user_id' => $userId,
					'Token.type' => 'activation'
				)
			)
		);
		return ($return > 0) ? true: false;
	}
	
	function createActivationCode($userId = null) {
		if(isset($userId)) {
			$hash = sha1($userId.microtime());
			$data = array(
				'Token' => array(
					'user_id' => $userId,
					'type' => 'activation',
					'value' => $hash,
					'expires' => date('Y-m-d H:i:s', strtotime("+24 hours"))
				)
			);
			if($this->save($data)) {
				return $hash;
			}
		}
		return false;
	}
}
?>
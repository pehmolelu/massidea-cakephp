<?php
/* Token Test cases generated on: 2011-06-20 15:06:08 : 1308572948*/
App::import('Model', 'Token');

class TokenTestCase extends CakeTestCase {
	var $fixtures = array('app.token', 'app.users', 'app.languages', 'app.deny_translation', 'app.privilege', 'app.profile', 'app.group', 'app.profiles_group', 'app.groups_users');

	function startTest() {
		$this->Token =& ClassRegistry::init('Token');
	}

	function endTest() {
		unset($this->Token);
		ClassRegistry::flush();
	}

}
?>
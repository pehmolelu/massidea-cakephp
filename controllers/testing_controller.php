<?php
/**
 *  TestingController
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free
 *  Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 *  License text found in /license/
 */

/**
 *  TestingController - class
 *  Controller for debugging and testing various things
 *  
 *  +------------------------------------------------------------+
 *  | This should be excluded entirely from production version ! |
 *	+------------------------------------------------------------+
 *
 *  @package        controllers
 *  @author         Jaakko Paukamainen
 *  @copyright      Jaakko Paukamainen
 *  @license        GPL v2
 *  @version        1.0
 */

class TestingController extends AppController {
	public $name = 'Users';
	public $components = array(); 
	public $helpers = array();
	
	function beforeFilter() {
		parent::beforeFilter();
		// If in production mode, redirect to homepage
		if(Configure::read('debug') == 0) {
			$this->redirect('/');
		}
		$this->autoRender = false;
		$this->autoLayout = false;		
	}
	
	function createOldUser($username = null, $password = null) {
		$email = substr(md5(rand(11111,99999)),0,5)."@mailinator.com";
		$salt = 'suola';
		$hashed = $this->Auth->hashOldPassword($salt, $password);
		$data = array(
			'User' => array(
				'languages_id' => 'my',
				'country_id' => 'GB',
				'username' => $username,
				'password' => $hashed,
				'password_salt' => $salt,
				'email' => $email
			)
		);
		debug($data);
		$success = $this->User->save($data, array(
			'validate' => false,
			'callbacks' => false
		));
		var_dump($success);
		debug($this->User->validationErrors);
	}
	
}
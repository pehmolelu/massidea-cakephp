<?php
/**
 * CustomAuth - Custom AuthComponent extension
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 * more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 * Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * License text found in /license/
 */

/**
 *  custom_auth -  class
 *
 *  @package    Components
 *  @author     Jaakko Paukamainen
 *  @copyright  2011 Jaakko Paukamainen
 *  @license    GPL v2
 *  @version    1.0
 */ 

App::import('Component', 'Auth');

class CustomAuthComponent extends AuthComponent {
	var $User;
	var $Token;
	
	function __construct() {
		parent::__construct();
		$this->User = ClassRegistry::init('User');
		$this->Token = ClassRegistry::init('Token');
	}
	
	function login($data = null) {
		if(empty($data)) $data = $this->data;
		$userId = $this->User->find('list', array(
			'conditions' => array('User.username' => $data['User.username']),
			'fields' => array('User.id')
		));
		$userId = current($userId);
		
		if($this->User->isOldUser($data['User.username'])) {
			$plainPassword = $data['User.password'];
			$salt = $this->User->getUserSalt($data['User.username']);
			$data['User.password'] = $this->hashOldPassword($salt, $data['User.password']);
			if($user = $this->identify($data)) {
				/*
				$userId = $this->User->find('list', array(
					'conditions' => array('User.username' => $data['User.username']),
					'fields' => array('User.id')
				));
				$userId = current($userId);
				*/
				$data['User.password'] = $this->updateToNewSalting($userId, $plainPassword);
			}
		} else {
			$data['User.password'] = $this->customHashPassword($data['User.password']);
		}
		
		if($success = $this->identify($data)) {
			// Continue authentication
			if($this->Token->hasPendingActivation($userId)) {
				$this->Session->setFlash(__('Login failed due to pending email verification.', true));
				//$this->redirect('/users/login');
				$success = false;
			} else {
				if($success = parent::login($data)) {
					$this->Session->setFlash(__('Successfully logged in!', true));
				}
			}
		}
		
		return $success;
	}
	
	function updateToNewSalting($userId = null, $plainPwd = null) {
		$data = array(
			'id' => $userId,
			'password' => $plainPwd,
			'password_salt' => ''
		);
		// Update user record for new hash (hashing occurs in UserModel, beforeSave())
		$savedData = $this->User->save($data, array(
			'validate' => false, 
			'fieldList' => array('password', 'password_salt')
		));
		if(!empty($savedData)) {
			CakeLog::write('activity', 'Updated password hashing for user id '.$userId);
			return $savedData['User']['password'];
		} else {
			return false;
		}
	}

	/**
	 * customHashPassword
	 * 
	 * Password hashing function.
	 * @param string $pwd password
	 * @return string
	 */
	function customHashPassword($pwd = null) {
		return Security::hash($pwd);
	}
	
	/**
	 * hashOldPassword
	 * 
	 * Hash password with old hashing algorithm (used in previous version of Massidea)
	 * @param string $salt
	 * @param string $password
	 * @return string
	 */
	function hashOldPassword($salt = null, $password = null) {
		return md5($salt.$password.$salt);
	}
	
	
}
/*
		if(!empty($this->data)) {
			// Get user record by username
			$dbUserData = $this->User->findByUsername($this->data['User']['username']);
			
			if(!empty($dbUserData)) {
				$isOldUser = $dbUserData['User']['password_salt'] != '';
				$plainPassword = $this->Auth->data['User']['password'];
				// Find out what kind of hashing is used
				if(!$isOldUser) {
					// Hash password
					$this->data['User']['password'] = $plainPassword;
					$this->data = $this->User->customHashPasswords($this->data);
				} else {
					// Fallback, try to identify with older hashing algorithm
					$this->data = $this->User->hashOldPassword($dbUserData, $plainPassword);
					// If user identifies with old hashing algorithm
					if($this->Auth->identify($this->data)) {
						// Update user record for new hash (hashing occurs in UserModel, beforeSave())
						$this->data['User']['password'] = $plainPassword;
						$this->data['User']['password_salt'] = '';
						$this->data = $this->User->save($this->data, array(
							'validate' => false, 
							'fieldList' => array('password', 'password_salt')
						));
						if(!empty($this->data)) CakeLog::write('activity', 'Updated password hashing for user '.$this->data['User']['username']);
					}
				}
				// Continue authentication
				if(!$this->Token->hasPendingActivation($dbUserData['User']['id'])) {
					if($this->Auth->login($this->data)) {
						$this->Session->setFlash(__('Successfully logged in!', true));
						$this->redirect('/');
					}
				} else {
					$this->Session->setFlash(__('Login failed due to pending email verification.', true));
					$this->redirect('/users/login');
				}
			}
		}
 */
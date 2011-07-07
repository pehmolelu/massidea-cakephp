<?php
/**
 *  PrivateMessagesController
 *
 *  This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 *  warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 *  You should have received a copy of the GNU General Public License along with this program; if not, write to the Free
 *  Software Foundation, Inc., 59 Temple P	lace, Suite 330, Boston, MA 02111-1307 USA
 *
 *  License text found in /license/
 */

/**
 *  PrivateMessages - class
 *  Handles private messages
 *
 *  @package        controllers
 *  @author         Jari Korpela
 *  @copyright
 *  @license        GPL v2
 *  @version        1.0
 */

class PrivateMessagesController extends AppController {

	var $components = array('RequestHandler');

	public function beforeFilter() {
		parent::beforeFilter();
		if(!$this->userId) {
			$this->redirect('/');
		}

	}
	
	protected function _isOwnerOf($userId, $messageId) {
		$message = $this->PrivateMessage->find('first',
			array('conditions' =>
				array('PrivateMessage.id' => $messageId, 'PrivateMessage.receiver'=>$userId)
			)
		);
		if (empty($message)) {
			return false;
		} else {
			return true;
		}
		
	}
	

	public function send() {

		$this->autoRender = false;
		$this->autoLayout = false;
		if ($this->RequestHandler->isAjax()) {
			if (!empty($this->data)) {
				//When users are ready this needs validation because data can be altered by end user.
				$this->data['PrivateMessage']['sender'] = $this->userId; //replaced with user id from session!
				$this->data['PrivateMessage']['message'] = trim($this->data['PrivateMessage']['message']);
				$this->data['PrivateMessage']['title'] = trim($this->data['PrivateMessage']['title']);
				
				if($this->PrivateMessage->save($this->data)) {

					echo 1;
				} else {
					die;
				}
			} else {
				$this->redirect('/');
			}
		} else {
			$this->redirect('/');
		}
	}

	public function delete(){
		$this->autoRender = false;
		$this->autoLayout = false;
		if ($this->RequestHandler->isAjax()) {
			if($this->_isOwnerOf($this->userId, $this->data['messageId'])) {
				if($this->PrivateMessage->delete($this->data['messageId'])) {
					echo 1;
				} else {
					echo 0;
				}
			} else {
				$this->redirect('/');
			}
		} else {
			$this->redirect('/');
		}
	}

	
	public function browse() {
		
		$messages_in_count = $this->PrivateMessage->find('count', array('conditions' => array('receiver' => $this->userId)) );
		$messages_out_count = $this->PrivateMessage->find('count', array('conditions' => array('sender' => $this->userId)) );
		$this->set('content_sidebar','left');
		$this->set('messages_in_count', $messages_in_count);
		$this->set('messages_out_count', $messages_out_count);
		$this->set('notifications_count', '0');
		
		
	}

	/*
	 * Array
	(
		[0] => Array
		(
			[User] => Array
			(
				[username] => sami
			)

			[PrivateMessage] => Array
			(
				[title] =>
				[created] => 2011-06-14 15:48:42
			)

		)
	*/
	public function fetch_messages(){
// 		$this->RequestHandler->setContent('json', 'text/x-json');
		$id=$this->userId;
		$this->autoRender = false;
// 		$this->autoLayout = false;
		App::import('Helper', 'smart_time');
		$time = new SmarttimeHelper();
		//debug($this->PrivateMessage->find('all'));die;
		$messages=$this->PrivateMessage->find('all',array(
				'conditions' => array('PrivateMessage.receiver' => $id),
				'fields' => array('UserSender.username','PrivateMessage.sender', 'PrivateMessage.id', 'message', 'title','created')
		));
		foreach ($messages as $k => $message) {
			$time->end = '+1 day';
			$messages[$k]['PrivateMessage']['timeago'] =
			$time->timeAgoInWords($message['PrivateMessage']['created']);
			$messages[$k]['PrivateMessage']['message'] = htmlspecialchars($message['PrivateMessage']['message']);
			$messages[$k]['PrivateMessage']['title'] = htmlspecialchars($message['PrivateMessage']['title']);
		}
		echo json_encode($messages);
	}

}
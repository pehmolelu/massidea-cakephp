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
	//var $uses = array('UserPrivateMessage');

	public function beforeFilter() {
		parent::beforeFilter();
		if(!$this->userId) {
			$this->redirect('/');
		}

	}

	
	protected function _isOwnerOf($userId, $messageId, $checkReceiver = null) {
		if (!$checkReceiver === false) {
			$message = $this->PrivateMessage->UserPrivateMessage->find('first',
			array('conditions' => array(
					'private_message_id' => $messageId,
					'receiver_id'=>$userId),
				  'recursive' => '-1'				
			)
			);
			
			$id = (isset($message['UserPrivateMessage']['id'])) ? $message['UserPrivateMessage']['id'] : null;
		}	

		if (!$checkReceiver === true) {
			$message = $this->PrivateMessage->find('first',
			array('conditions' =>
			array('id' => $messageId, 'sender_id'=>$userId)
			)
			);			
			$id = (isset($message['PrivateMessage']['id'])) ? $message['PrivateMessage']['id'] : null;
		}

		if (empty($id)) {
			return false;
		} else {
			return $id;
		}

	}
	
// 	protected function _isValidPage($page) {
// 		if ($page === 'inbox' || $page === 'sent') {
// 			return  true;
// 		} else {
// 			return false;
// 		}
// 	}
	
	

	public function send() {

		$this->autoRender = false;
		$this->autoLayout = false;
		if ($this->RequestHandler->isAjax()) {
			if (!empty($this->data)) {
				//When users are ready this needs validation because data can be altered by end user.
				$this->data['PrivateMessage']['sender_id'] = $this->userId; //replaced with user id from session!
				$this->data['PrivateMessage']['message'] = trim($this->data['PrivateMessage']['message']);
				$this->data['PrivateMessage']['title'] = trim($this->data['PrivateMessage']['title']);
				if($this->PrivateMessage->save($this->data)) {
					$this->data['UserPrivateMessage']['private_message_id'] = $this->PrivateMessage->id;
					$this->PrivateMessage->UserPrivateMessage->save($this->data);
						
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

	public function delete_inbox(){
		$this->autoRender = false;
		$this->autoLayout = false;

		if ($this->RequestHandler->isAjax()) {

			if($id = $this->_isOwnerOf($this->userId, $this->data['messageId'], true)) {
				
				$this->PrivateMessage->UserPrivateMessage->set(array('id' => $id,'deleted' => true));
				$this->PrivateMessage->UserPrivateMessage->save();
				echo 1;

			} else {
				echo 0;
			}
		} else {
			$this->redirect('/');
		}
	}

// 	public function delete_(){
// 		$this->autoRender = false;
// 		$this->autoLayout = false;
// 		debug($this->data); die;
// 		if ($this->RequestHandler->isAjax()) {
// 			if($this->_isOwnerOf($this->userId, $this->data['messageId'])) {
	
// 				$this->PrivateMessage->UserPrivateMessage->save()
// 				echo 1;
// 			} else {
// 				echo 0;
// 			}
// 		} else {
// 			$this->redirect('/');
// 		}
// 	} else {
// 		$this->redirect('/');
// 	}
// 	}
	
// 	public function delete(){
// 		$this->autoRender = false;
// 		$this->autoLayout = false;
// 		debug($this->data); die;
// 		if ($this->RequestHandler->isAjax()) {
// 			if($this->_isOwnerOf($this->userId, $this->data['messageId'])) {
	
// 				$this->PrivateMessage->UserPrivateMessage->save()
// 				echo 1;
// 			} else {
// 				echo 0;
// 			}
// 		} else {
// 			$this->redirect('/');
// 		}
// 	} else {
// 		$this->redirect('/');
// 	}
// 	}

	
	public function browse() {
		$messages_in_count = $this->PrivateMessage->UserPrivateMessage->find('count', array('conditions' => array('receiver_id' => $this->userId)) );
		$messages_out_count = $this->PrivateMessage->find('count', array('conditions' => array('PrivateMessage.sender_id' => $this->userId)) );
		$this->set('content_sidebar','left');
		$this->set('messages_in_count', $messages_in_count);
		$this->set('messages_out_count', $messages_out_count);
		$this->set('notifications_count', '0');
		$this->set('page',$this->params['page']);

	}
	public function foo() {
		$this->autoRender = false;
		$this->autoLayout = false;
		$messages_out_count = $this->PrivateMessage->find('all');//, array('conditions' => array('sender_id' => $this->userId)) );
		debug($messages_out_count);
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
		$id=$this->userId;
		$this->autoRender = false;
		App::import('Helper', 'smart_time');
		$time = new SmarttimeHelper();
		if($this->params['page'] == 'sent' ) {
			$messages=$this->PrivateMessage->find('all',array(
							'conditions' => array('PrivateMessage.sender' => $id),
							'fields' => array('UserReceiver.username','PrivateMessage.receiver', 'PrivateMessage.id', 'message', 'title','created')
			));
				
		} else {
			//$this->UserPrivateMessage->contain();
			$messages=$this->PrivateMessage->UserPrivateMessage->find('all',array(
								'contain' => array(
									'PrivateMessage.Sender' => array(
										'fields' => array('username')
									),
 									'Receiver'=> array(
 										'fields' => array('username')										
 									),
 									'PrivateMessage' => array(
 										'fields' => array('id','sender_id','parent_id','read_receipt','title','message','created')
 									),
									'PrivateMessageTag'),
								'conditions' => array('Receiver.id' => $id, 'UserPrivateMessage.deleted' => false)
			));
// 			debug($messages); die;
		}
		foreach ($messages as $k => $message) {
			$time->end = '+1 day';
			$messages[$k]['PrivateMessage']['timeago'] =
			$time->timeAgoInWords($message['PrivateMessage']['created']);
			$messages[$k]['PrivateMessage']['message'] = htmlspecialchars($message['PrivateMessage']['message']);
			$messages[$k]['PrivateMessage']['title'] = htmlspecialchars($message['PrivateMessage']['title']);
		}
		
		echo json_encode(array('messages' => $messages));
	}


}

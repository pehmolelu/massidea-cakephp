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
 *  @author         Manu Bamba
 *  @copyright
 *  @license        GPL v2
 *  @version        1.0
 */

class PrivateMessageTagsController extends AppController {

	var $components = array('RequestHandler', 'PrivateMessage_');

	public function beforeFilter() {
		parent::beforeFilter();
		if(!$this->userId) {
			$this->redirect('/');
		}
		$this->autoRender = false;
		$this->autoLayout = false;
	}
	
	public function add() {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($this->data)) {
				$this->PrivateMessageTag->set(array('user_id' => $this->userId));
				$this->PrivateMessageTag->save($this->data);
				$tagId = $this->PrivateMessageTag->id;
				echo json_encode($this->PrivateMessageTag->find('first', array(
					'conditions' => array(
						'id' => $tagId
					),
					'recursive' => '-1'
				)));
			}		
		}
	}
	
	public function view($tagId = null) {
		$allTags = $this->PrivateMessageTag->find('all', array(
			'conditions' => array(
				'user_id' => $this->userId
			),
			'recursive' => '-1',	
			'order' => 'name ASC'			
		));
		echo json_encode($allTags);
	}
	
	public function tag() {
		foreach ($this->data['messageId'] as $messageId) {
			if($privateMessageId = $this->PrivateMessage_->_isOwnerOf($this->userId, $messageId, false)) {
				$this->data['OutboxTag']['private_message_id'] = $userPrivateMessageId;
				$this->data['OutboxTag']['private_message_tag_id'] = $this->data['tagId'];
				if ($this->PrivateMessageTag->OutboxTag->save($this->data)) {
					$this->PrivateMessageTag->OutboxTag->id = null;
					echo 1;
				} else {
					echo 0;
				}
			}
			elseif($userPrivateMessageId = $this->PrivateMessage_->_isOwnerOf($this->userId, $messageId, true)) {
				$this->data['InboxTag']['user_private_message_id'] = $userPrivateMessageId;
				$this->data['InboxTag']['private_message_tag_id'] = $this->data['tagId'];
				if($this->PrivateMessageTag->InboxTag->save($this->data)) {
					$this->PrivateMessageTag->InboxTag->id = null;
					echo 1;
				} else {
					echo 0;
				}
			} else {
				echo 0;
			}
		}		
	}
	
	public function delete() {
		foreach ($this->data['tagId'] as $tagId) {
			if($this->_isOwnerOf($tagId)) {
				$this->PrivateMessageTag->id = $tagId;
				if($this->PrivateMessageTag->delete()) {
					echo 1;
				} else {
					echo 0;
				}
				
			}
		}
	}
	
	protected function _isOwnerOf($tagId) {
		$this->PrivateMessageTag->id = $tagId;
		if ($this->PrivateMessageTag->find('first', array(
			'conditions' => array(
				'user_id' => $this->userId
			)
		))) {
			return true;
		} else {
			return false;
		}
	}
}
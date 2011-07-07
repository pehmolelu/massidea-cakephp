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

	}

	public function send() {

		$this->autoRender = false;
		$this->autoLayout = false;
		if ($this->RequestHandler->isAjax()) {
			if (!empty($this->data)) {
				//When users are ready this needs validation because data can be altered by end user.
				$this->data['PrivateMessage']['sender'] = $this->userId; //replaced with user id from session!
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

	}

	public function browse() {
		$this->set('content_sidebar','left');
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
		$this->autoRender = false;
		$this->autoLayout = false;
		App::import('Helper', 'smart_time');
		$time = new SmarttimeHelper();
		$id=$this->Session->read('Auth.User.id');
		$messages=$this->PrivateMessage->find('all',array('fields'=>array('User.username','id', 'title','created')));
		foreach ($messages as $k => $message) {
			$time->end = '+1 day';
			$messages[$k]['PrivateMessage']['timeago'] =
			$time->timeAgoInWords($message['PrivateMessage']['created']);

		}
		echo json_encode($messages);
	}

}
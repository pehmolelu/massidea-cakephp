<?php
/**
 *  ContentsController
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
 *  ContentController - class
 *  Maintains actions for browsing, adding and viewin contents
 *	TODO: User checks when users done.
 *  @package        controllers
 *  @author         Jari Korpela
 *  @copyright      Jari Korpela
 *  @license        GPL v2
 *  @version        1.0
 */

class ContentsController extends AppController {
	
	public $validContentTypes = array('challenge','idea','vision');
	
	public $components = array('Cookie','Cookievalidation','Content_','Tag_','Company_');
	public $uses = array('Contents','Language','LinkedContent','Tags', 'RelatedCompanies','Baseclasses');
	
	public function beforeFilter() {
		parent::beforeFilter();		
	}
		
	/**
	 * browse Action - method
	 * Lists contents.
	 * 
	 * Routes that direct to this action are:
	 * Router::connect('/', array('controller' => 'contents', 'action' => 'browse', 'index'));
	 * Router::connect('/contents/challenge/', array('controller' => 'contents', 'action' => 'browse', 'challenge'));
	 * Router::connect('/contents/idea/', array('controller' => 'contents', 'action' => 'browse', 'idea'));
	 * Router::connect('/contents/vision/', array('controller' => 'contents', 'action' => 'browse', 'vision'));
	 * Router::connect('/contents/*', array('controller' => 'contents', 'action' => 'browse'));
	 * 
	 * @author	Jari Korpela
	 * @param	enum $content_type Accepted values: 'all', 'challenge', 'idea', 'vision'
	 */
	public function browse($contentType = 'all') {
		$users = array('table' => 'users', 'alias' => 'User', 'type' => 'left', 'conditions' => array("User.id = Privileges.creator"));
		$lang = array('table' => 'languages', 'alias' => 'Language', 'type' => 'left', 'conditions' => array("Contents.language_id = Language.id"));
		$this->Nodes->join = array($users, $lang);
		
		if($contentType = $this->Content_->validateContentType($contentType)) { 
			$contents = $this->Nodes->find(array('type' => 'Content', 'class' => $contentType),array('limit' => 10, 'order' => 'Contents.created DESC'),true);
		}
		else {
			$contentType = 'all';
			$contents = $this->Nodes->find(array('type' => 'Content'),array('limit' => 10, 'order' => 'Contents.created DESC'),true);
		}
		$userIdsAndCounts = array();
		
		foreach($contents as $content) {
			if(isset($content['User']['id']) && !isset($userIdsAndCounts[$content['User']['id']])) {
				$userIdsAndCounts[$content['User']['id']] = $this->Baseclasses->find('count',array('conditions' => array('type' => 'Content', 'creator' => $content['User']['id'])));
			}
		}
		
		$this->set('contentCounts',$userIdsAndCounts);
		$this->set('content_type',$contentType);
		$this->set('contents',$contents);
	}

	/**
	 * add action - method
	 * Adds content
	 * 
	 * Routes that direct to this action are:
	 * Router::connect('/contents/add/*', array('controller' => 'contents', 'action' => 'add'));
	 * 
	 * @author	Jari Korpela
	 * @param	enum $content_type Accepted values: 'all', 'challenge', 'idea', 'vision'
	 * @param	int	$related To what content this content will be linked to
	 */
	public function add($contentType = 'challenge', $related = 0) {

		if(isset($this->userId)) {
			
			if(!$contentType = $this->Content_->validateContentType($contentType)) { //We validate the contentType received from url to prevent XSS.
				$this->redirect('/');
			}
		
			if (!empty($this->data)) { // If form has been posted
				$this->data['Node']['type'] = 'Content'; //Set Node type
				$this->data['Privileges']['creator'] = $this->userId; // Set creator to the logged in user
				
				if($this->data['Node']['published'] == 1) {
					$this->data['Privileges']['privileges'] = '755'; // Set basic privileges
				} else {
					$this->data['Privileges']['privileges'] = '700'; // Set privileges so that other users cant read it
				}
				
				if(in_array($contentType,$this->validContentTypes)) {
					$this->data['Node']['class'] = $contentType;
				}			
				
				$this->Content_->setAllContentDataForSave($this->data);
				$this->Tag_->setTagsForSave($this->data['Tags']['tags'],$this->data['Privileges']);
				$this->Company_->setCompaniesForSave($this->data['Companies']['companies'],$this->data['Privileges']);
	
	
				if($this->Content_->saveContent() !== false) { //If saving the content was successfull then...
					//TODO: This area is missing a method to link the $related content to this content. Should be done when the link method is ready.
					
					$this->Tag_->linkTagsToObject($this->Content_->getContentId()); //We have content ID after content has been saved
					$this->Company_->linkCompaniesToObject($this->Content_->getContentId());
	
					$this->Session->setFlash('Your content has been successfully saved.', 'flash'.DS.'successfull_operation');
	
					if($this->Content_->getContentPublishedStatus() === "1") {
						$this->redirect(array('controller' => 'contents', 'action' => 'view', $this->Content_->getContentId()));
					} else {
						$this->redirect(array('controller' => 'contents', 'action' => 'edit', $this->Content_->getContentId()));
					}
				} else {
					$this->Session->setFlash('Your content has NOT been successfully saved.');
				}
			}
			
			//$this->helpers[] = 'TinyMce.TinyMce'; //Commented out for future use...
			

			$this->set('language_list',$this->Language->find('list',array('order' => array('Language.name' => 'ASC'))));
			$this->set('content_type',$contentType);

		} else {
			$this->redirect('/');
		} 
		
	}
	
	/**
	 * edit action - method
	 * Edits content
	 * 
	 * Routes that direct to this action are:
	 * Router::connect('/contents/edit/*', array('controller' => 'contents', 'action' => 'edit'));
	 * 
	 * @author	Jari Korpela
	 * @param	int $contentId
	 */
	public function edit($contentId = -1) {
		if(isset($this->userId)) {
			
			if (!empty($this->data)) { // If form has been posted
				$this->Nodes->cache = false;

				//check that the content id is owned by the user
				$content = $this->Nodes->find(array('type' => 'Content', 'Privileges.creator' => $this->userId, 'Privileges.id' => $contentId),array(),true);
				
				if(empty($content)) {
					$this->Session->setFlash('You dont own that content so go away!');
					$this->redirect('/');
				}
				
				$this->data['Privileges'] = $content[0]['Privileges'];
				
				if($this->data['Node']['published'] == 1) {
					$this->data['Privileges']['privileges'] = '755'; // Set basic privileges
				} else {
					$this->data['Privileges']['privileges'] = '700'; // Set privileges so that other users cant read it
				}
				
				$this->data['Node']['type'] = 'Content';
				$this->data['Node']['id'] = $contentId;
				$this->data['Node']['class'] = $content[0]['Node']['class'];
				
				//$this->data['Privileges']['creator'] = NULL;
				$this->Content_->setAllContentDataForSave($this->data);
	
				$this->Tag_->setTagsForSave($this->data['Tags']['tags'],array('privileges' => 555, 'creator' => $this->userId));
				$this->Company_->setCompaniesForSave($this->data['Companies']['companies'],array('privileges' => 555, 'creator' => $this->userId));
				
				$contentBeforeSave = $content;
				$childsToDelete = $contentBeforeSave[0]['Child'];				
	
				foreach($childsToDelete as $key => $child) {
					foreach($this->Tag_->getNewAndExistingTags() as $tag) {
						if($child['id'] == $tag['Node']['id']) {
							unset($childsToDelete[$key]); continue 2;
						}
					}
					foreach($this->Company_->getNewAndExistingCompanies() as $company) {
						if($child['id'] == $company['Node']['id']) {
							unset($childsToDelete[$key]); continue 2;
						}
					}
				}
				
				if($this->Content_->saveContent() !== false) { //If saving the content was successfull then...
					
					$this->Content_->removeChildsFromContent($childsToDelete);
					$this->Tag_->linkTagsToObject($this->Content_->getContentId()); //We have content ID after content has been saved
					$this->Company_->linkCompaniesToObject($this->Content_->getContentId());
					
					$errors = array();		
					if(empty($errors)) {
						$this->Session->setFlash('Your content has been successfully saved.', 'flash'.DS.'successfull_operation');
						
					} else {
						$this->Session->setFlash('Your content has NOT been successfully saved.');
					}
	
					if($this->Content_->getContentPublishedStatus() === "1") {
						$this->redirect(array('controller' => 'contents', 'action' => 'view', $this->Content_->getContentId()));
					} else {
						$this->redirect(array('controller' => 'contents', 'action' => 'edit',$contentId));
					}
	
				} else {
					$this->Session->setFlash('Your content has NOT been successfully saved.');
					$this->redirect('edit/'.$contentId);
				}
			} else {
				if($contentId == -1) {
					$this->redirect('/');
				}
				$this->Nodes->cache = false;
				$content = $this->Nodes->find(array('type' => 'Content', 'Contents.id' => $contentId),array(),true);
				
				if(empty($content)) {
					$this->Session->setFlash('Invalid content ID');
					$this->redirect('/');
				} else {
					$this->Content_->setAllContentDataForEdit($content[0]);
					$editData = $this->Content_->getContentDataForEdit();
					$this->data = $editData;
				}
				$this->set('language_list',$this->Language->find('list',array('order' => array('Language.name' => 'ASC'))));
				$this->set('content_type',$content[0]['Node']['class']);
			}
		} else {
			$this->redirect('/');
		}
	}
	
	/**
	 * view action - method
	 * Views content
	 * 
	 * @author	Jari Korpela
	 * @param	int $contentId
	 */
	public function view($contentId = -1) {
		if($contentId == -1) {
			$this->redirect('/');
		}
		$this->set('content_class','contentWithTopAndSidebar');		
		$this->Nodes->cache = false;
		$users = array('table' => 'users', 'alias' => 'User', 'type' => 'left', 'conditions' => array("User.id = Privileges.creator"));
		$lang = array('table' => 'languages', 'alias' => 'Language', 'type' => 'left', 'conditions' => array("Contents.language_id = Language.id"));
		$this->Nodes->join = array($users, $lang);

		$content = $this->Nodes->find(array('type' => 'Content', 'Contents.id' => $contentId));
		if(empty($content)) {
			$this->Session->setFlash('Invalid content ID');
			$this->redirect('/');
		}
		//debug($content);
		$content = $content[0];
		$contentUserId = $content['User']['id'];
		$isOwner = $contentUserId == $this->userId;
		
		$contentUsername = $content['User']['username'];
		
		$contentSpecificData = $this->Content_->getContentSpecificDataFromData($content['Node']['data']);
		$tags = array();
		$relatedCompanies = array();
		if(isset($content['Child'])) {
			foreach($content['Child'] as $child) {
				if($child['type'] == 'Tag') {
					$tags[] = $child;
				} elseif ($child['type'] == 'RelatedCompany') {
					$relatedCompanies[] = $child;
				}
			}
		}
		
		
		$l1 = array(
			'table' => 'linked_contents',
			'alias' => 'LinkedContent',
			'type' => 'left',
			'conditions' => array(
				"LinkedContent.to = Contents.Id"
			)
		);
		
		$l2 = array(
			'table' => 'users',
			'alias' => 'User',
			'type' => 'left',
			'conditions' => array(
				"User.id = Privileges.creator"
			)
		);
		
		$this->Nodes->join = array($l1,$l2);
		$linkedContents = $this->Nodes->find(array(
			'type' => 'Content',
			),
			array(
				'conditions' => array(
					"LinkedContent.from" => $contentId
				),
				'order' => array('LinkedContent.created DESC')
			),
			false
		);
		
		$linkedContentsCount = sizeof($linkedContents);
		
		//debug($linkedContents);die;
		
												
		$cookies = $this->Cookievalidation->getAndValidateCookies('expandStatus');

		$this->set('cookies',$cookies);
		$this->set('isOwner',$isOwner);
		$this->set('contentId',$contentId);
		$this->set('content',$content['Node']);
		$this->set('contentUserId',$contentUserId);
		$this->set('contentUsername',$contentUsername);		
		$this->set('language',$content['Language']);
		$this->set('tags',$tags);
		$this->set('relatedCompanies',$relatedCompanies);
		$this->set('specific',$contentSpecificData);
		$this->set('linkedContents',$linkedContents);
		$this->set('linkedContentsCount',$linkedContentsCount);
		
	}
	
	/**
	 * delete action - method
	 * Deletes content
	 * 
	 * @author	
	 * @param
	 */
	public function delete($content_id) {
		
	}
	
	/**
	 * preview action - method
	 * Previews content
	 * 
	 * @author	
	 * @param
	 */
	public function preview($content_id) {
		
	}
	

}
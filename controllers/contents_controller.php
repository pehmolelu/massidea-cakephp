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
 *
 *  @package        controllers
 *  @author         
 *  @copyright      
 *  @license        GPL v2
 *  @version        1.0
 */

class ContentsController extends AppController {
	public $uses = null;
	
	public function beforeFilter() {
		parent::beforeFilter();
	}
	
	/**
	 * index Action - method
	 * Lists contents for browsing
	 * 
	 * @author	
	 * @param	
	 */
	public function index() {

	}

	/**
	 * add action - method
	 * Adds content to database using specific form
	 * 
	 * @author	
	 * @param
	 */
	public function add() {
		
	}
	
	/**
	 * edit action - method
	 * Edits content from database using specific form
	 * 
	 * @author	
	 * @param
	 */
	public function edit($content_id) {
		
	}
	
	/**
	 * delete action - method
	 * 
	 * 
	 * @author	
	 * @param
	 */
	public function delete($content_id) {
		
	}
	
	/**
	 * preview action - method
	 * 
	 * 
	 * @author	
	 * @param
	 */
	public function preview($content_id) {
		
	}
	
	/**
	 * flag action - method
	 * 
	 * 
	 * @author	
	 * @param
	 */
	public function flag($content_id) {
		
	}
	

}
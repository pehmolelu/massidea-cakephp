<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */


Router::connect('/', array('controller' => 'contents', 'action' => 'browse', 'all'));
	Router::connect('/contents/view/*', array('controller' => 'contents', 'action' => 'view'));
	Router::connect('/contents/edit/*', array('controller' => 'contents', 'action' => 'edit'));
	Router::connect('/contents/add/*', array('controller' => 'contents', 'action' => 'add'));
	Router::connect('/contents/challenge', array('controller' => 'contents', 'action' => 'browse', 'challenge'));
	Router::connect('/contents/idea', array('controller' => 'contents', 'action' => 'browse', 'idea'));
	Router::connect('/contents/vision', array('controller' => 'contents', 'action' => 'browse', 'vision'));
	Router::connect('/contents/*', array('controller' => 'contents', 'action' => 'browse')); //This is needed to route all other traffic to browse
	

	Router::connect('/private_messages/fetch_messages_conversation/:associate_id',
		array('controller' => 'private_messages', 'action' => 'fetch_messages_conversation'
			));
	
	
	Router::connect('/private_messages/fetch_messages_thread/:associate_id',
	array('controller' => 'private_messages', 'action' => 'fetch_messages_thread'
				));
	
	
	Router::connect('/private_messages/fetch_messages_tag/:tag_id',
	array('controller' => 'private_messages', 'action' => 'fetch_messages_tag'
	));
	
//	Router::connect('/private_messages/fetch_messages_:page',
//		array('controller' => 'private_messages', 'action' => 'fetch_messages_:page'),
//		array('page' => '(inbox)|(sent)|(myNotes)')
//	);
	
	Router::connect('/private_messages/send', array('controller' => 'private_messages', 'action' => 'send'));
	
	Router::connect('/private_messages/delete/:page/:associate_id',
	array('controller' => 'private_messages', 'action' => 'delete',
	array( 'page' => '(conversation)|(thread)')));
	
	Router::connect('/private_messages/delete/:page/*',
		array('controller' => 'private_messages', 'action' => 'delete',
		array( 'page' => '(inbox)|(sent)|(notes)')));
// 	Router::connect('/private_messages/*', array('controller' => 'private_messages', 'action' => 'browse', 'page' => 'inbox'));
	//Router::connect('/*', array('controller' => 'contents', 'action' => 'browse', 'index'));
	
	
	
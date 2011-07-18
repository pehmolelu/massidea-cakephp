<div id="title">
<!-- Links/Lynx fix -->
	<h1><a href="<?php echo $this->Html->url('/'); ?>"><span>Massidea.org</span></a></h1>
</div>

<div id="search">
	<div id="search-top">
		<?php if($this->Session->read('Auth.User')): ?>
			Logged in as <?php echo $this->Session->read('Auth.User.username'); ?><span class="grey"> |</span>
                       <?php echo $this->Html->link(
                               $html->image('icon_message_off.png',array('class' => 'icon', 'id'=>'message_link')),
                               array('controller'=>'private_messages', 'action'=>'inbox'),
                               array('escape' => false)); ?>

		<?php //echo $html->image('icon_message_off.png',array('class' => 'icon', 'id'=>'message_link')); ?>
		<span class="grey"> |</span><a href="#">
		<?php echo $html->image('notifications_a.png',array('class' => 'icon', 'id'=>'notification_link')); ?>
		</a> <span class="grey"> |</span>
			<?php echo $this->Html->link('Logout', '/users/logout', array('id' => 'logoutlink', 'class' => 'logoutLink')); ?>
		<?php else: ?>
			<?php echo $this->Html->link('Login', '/users/login', array('id' => 'loginlink', 'class' => 'loginLink')); ?> | 
			<?php echo $this->Html->link('Signup', '/users/signup', array('id' => 'signuplink', 'class' => 'signupLink')); ?>
		<?php endif;?>
	</div>
	<?php echo $this->element('layout'.DS.'forms'.DS.'globalsearch', array('cache' => true));  ?>
	<div class="clear"></div>
	<div id="select">
		<div class="left">
			<?php echo $this->element('layout'.DS.'forms'.DS.'languagechange', array('cache' => true));  ?>		
		</div>
		<div class="right"></div>
		<div class="clear"></div>
	</div>
</div>

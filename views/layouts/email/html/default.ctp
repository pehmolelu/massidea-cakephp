<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"> 
<html>
<body>
<?php echo $this->Html->image($this->Html->url('/img/massidea_logo.png', true), array('alt' => 'Massidea.org')); ?>
<?php echo $content_for_layout; ?> 
<br />
<hr />
<?php echo sprintf(__('This is an automated system message from %s, please do not reply to this address.', true), $this->Html->link('Massidea.org', 'http://massidea.org/')); ?>
</body>
</html>
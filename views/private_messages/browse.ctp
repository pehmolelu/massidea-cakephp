<?php 
echo $html->script('datatables'.DS.'jquery.dataTables.min',array('inline'=>false));
echo $html->script('datatables'.DS.'tabletools'.DS.'js'.DS.'TableTools.min',array('inline'=>false));
echo $html->script('datatables'.DS.'tabletools'.DS.'js'.DS.'ZeroClipboard',array('inline'=>false));
echo $this->element('global'.DS.'private_message', array('cache' => false));
echo $this->element('privatemessages'.DS.'create_tag', array('cache' => false));
echo $this->Html->css(array('..'.DS.'js'.DS.'datatables'.DS.'tabletools'.DS.'css'.DS.'TableTools_JUI'),'stylesheet', array('inline' => false ) );
echo $this->Html->scriptStart(array('inline'=>false));

echo 'var page = "'.$page.'";';
echo $this->Html->scriptEnd();
?>
<table id="PrivateMessages-table" width="100%" class="fixed-table display">
	<thead>
		<tr>
			
			<th >Id</th>
			<th width="4%" class="checkbox-header"><input type="checkbox"/></th>
			<th width="22%"><?php __('From') ?></th>
			<th width="55%"><?php __('Title') ?></th>
			<th>Time</th>
			<th width="18%"><?php __('Received') ?></th>
			<th>Message</th>
		</tr>
	</thead>
	<tbody>

	</tbody>
	<tfoot>
	<tr>			
			<th >Id</th>
			<th width="4%" class="checkbox-header"><input type="checkbox"/></th>
			<th width="22%"><?php __('From') ?></th>
			<th width="55%"><?php __('Title') ?></th>
			<th>Time</th>
			<th width="18%"><?php __('Received') ?></th>
			<th>Message</th>
		</tr>
	</tfoot>
</table>


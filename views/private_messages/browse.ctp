<?php 

echo $html->script('datatables'.DS.'jquery.dataTables.min',array('inline'=>false));
echo $this->element('global'.DS.'private_message', array('cache' => false));

?>
<table id="example" width="100%" class="fixed-table display">
	<thead>
		<tr>
			<th class="hidden">Id</th>
			<th width="20%"><?php __('From') ?></th>
			<th width="60%"><?php __('Title') ?></th>
			<th>Time</th>
			<th width="20%"><?php __('Received') ?></th>
		</tr>
	</thead>
	<tbody>

	</tbody>
</table>


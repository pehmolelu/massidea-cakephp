<?php echo $this->element('global'.DS.'private_message');
echo $html->script('datatables'.DS.'jquery.dataTables.min',array('inline'=>false));

?>
<table id="example" width="100%">
	<thead>
		<th>From</th>
		<th>Title</th>
		<th>Message</th>
	</thead>
	<tbody>
		<tr>
			<td>1</td>
			<td>2</td>
			<td>3</td>
		</tr>
	</tbody>
</table>

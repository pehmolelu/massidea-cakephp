$(document).ready(function() {
	$.ajax({ 
		type: 'POST',
		url: jsMeta.baseUrl+"/private_messages/browse/",
		success: function(data) {
			if(data) {
				setFlash("Message sent successfully",'successfull');
				showFlash();
			} else {
				setFlash("Failure in message send. Message not delivered");
				showFlash();
			}
		}
	});
	$('#example').dataTable( {
		"bProcessing": true,
		"sAjaxSource": "sources/arrays.txt",
		"bJQueryUI": true,
		"sPaginationType": "full_numbers"
	} );
} );
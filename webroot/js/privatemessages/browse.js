$(document).ready(function() {
	var oTable = $('#example').dataTable( {
		"aaSorting": [[2,'desc']],
		"bProcessing": true,
		"bJQueryUI": true,
		"bAutoWidth": false,
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			var id = aData.PrivateMessage.id;
			var sender = aData.User.username;
			if  (!aData.PrivateMessage.title) {
				aData.PrivateMessage.title = '&ltNo Title&gt';
				$('td:eq(1)',nRow).addClass('grey');
			}
			//$('td:eq(0)',nRow).html('bye').css('display','block').css('position','absolute');
			var actions = '\
				<div class="hidden">\
					<div class="inline">\
						 <a href="#">Read</a> | \
					</div>\
					<div class="send-message inline">\
						<a href="#">Reply</a> | \
						<input type="hidden" class="send-message-name" value="'+ sender +'" />\
						<input type="hidden" class="send-message-id" value="'+ id +'" />\
					</div>\
					<div class="inline">\
						<a href="#">Delete</a>\
					</div>\
				</div>\
				';
			
			
			$('td:eq(1)',nRow).html(aData.PrivateMessage.title + actions );
			return nRow;
			},
		"sAjaxSource": jsMeta.baseUrl+"/private_messages/fetch_messages/",
		"sAjaxDataProp": "", 
		"aoColumns": [
            { "mDataProp": "PrivateMessage.id", "bVisible": false},         
			{ "mDataProp": "User.username"},
			{ "mDataProp": "PrivateMessage.title", "sClass":"no-overflow padding-left-right"},
			{ "mDataProp": "PrivateMessage.created", "bVisible": false},			
			{ "mDataProp": "PrivateMessage.timeago" ,"iDataSort": 3,  "sClass":"center no-overflow"}
			]
	} );
	
	$('#example > tbody > tr').live("mouseover mouseout",function(e){
			if(e.type=="mouseover") {
				$('td:eq(1) > div',this).removeClass('hidden');
			} else {
				$('td:eq(1) > div',this).addClass('hidden');
			}
			
	});
	
	
	
	
} );


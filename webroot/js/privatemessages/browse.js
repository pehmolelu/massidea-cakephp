$(document).ready(function() {
	var dataTable = privateMessageBrowseInit();
	
	$('#PrivateMessages-table > tbody > tr').live("click mouseover mouseout",function(e){
		if(e.type=="mouseover") { //show the links for read/reply/delete
			$('td:eq(2) > div',this).removeClass('hidden');
		}

		if(e.type=="mouseout"){ // donot remove links for read/reply/delete if the message is open
			if($(this).next().children().hasClass('message_row')) {
				return;
			}			
			$('td:eq(2) > div',this).addClass('hidden');//remove links for read/reply/delete on mouseout	
		}
		if(e.type=="click") {
			if($(this).children().hasClass('message_row')) return;   //if clicking on the message row, no action is needed
			var classname = this.className;
			var element = e.target; //its the clicked element
			var tr = this;
			
			if(element.className == 'privatemessage-read') { //if the read link is clicked
				if($(this).next().children().hasClass('message_row')) { // if the message is already open when the read link is clicked
					$(this).removeClass('row_expanded');
					dataTable.fnClose(this);				
				} else { //if the message is not yet open, open it. The clicked row as well as the message_row are selected and the hidden class is removed from read/reply/delete links and the previous open message is closed 
					var aPos = dataTable.fnGetPosition(this); //the row number of clicked tr
					var message = '<div class="the_message hidden">';
					message += nl2br(dataTable.fnGetData(aPos).PrivateMessage.message,false);	
					message += '</div>';
			
					if($('.the_message').length) {

						toClose = $('td.message_row').parent().prev();
						toClose.removeClass('row_expanded');
						
						
						$('.the_message').slideToggle(300,function(){
								dataTable.fnClose(toClose[0]);
								var new_row = dataTable.fnOpen( tr, message, "message_row");
								$(new_row).addClass('even');
								$('.the_message',$(tr).next()).slideToggle(300);
						});
						
						
					} else {
						var new_row = dataTable.fnOpen( this, message, "message_row");
						console.log($(tr));
						console.log(new_row);
						//new_row.addclass(tr.classList());
						$(this).addClass('row_expanded pointerCursor');
						$('.the_message',$(this).next()).slideToggle(300);
					}
					
				}
				return false;
			}


			if(element.className == 'privatemessage-delete') {
				var aPos = dataTable.fnGetPosition(this);
				var id = dataTable.fnGetData(aPos).PrivateMessage.id;
				dataTable.fnDeleteRow(aPos,privateMessageDelete(id));
				return false;
			}
			if($(this).hasClass('row_expanded')) {  //if the clicked row is selected already, de-select the row by removing the row_selected class 
				$(this).removeClass('row_expanded');
				$(this).removeClass('pointerCursor') ;
				dataTable.fnClose(this);
				return false;
			} 
			
			if($(this).hasClass('row_selected')) {  //if the clicked row is selected already, de-select the row by removing the row_selected class 
				$(this).removeClass('row_selected');
				$(this).removeClass('pointerCursor') ;
				dataTable.fnClose(this);
			} else { // if the row is not already selected, selects the row by applying row_selected class
				$(this).addClass('row_selected');       
			}
			
			
			
			
		}
	});




} );

function privateMessageDelete(id) {
	$.ajax({ 
		type: 'POST',
		data: {data:{messageId:id}},
		url: jsMeta.baseUrl+"/private_messages/delete/",
		success: function(data) {
			if(data) {
				setFlash("Message deleted successfully",'successfull');
				showFlash();
			} else {
				setFlash("Failure in message delete. Message not deleted");
				showFlash();
			}
		}
	});

}

function privateMessageRead(id) {


}



function privateMessageBrowseInit() {
	var oTable = $('#PrivateMessages-table').dataTable( {
		"oSearch": {"sSearch": ""},  
		"aaSorting": [[4,'desc']],
		"bProcessing": true,
		"bJQueryUI": true,
		"bAutoWidth": false,
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			
			var id = aData.PrivateMessage.sender;
			var sender = aData.UserSender.username;
			if  (!aData.PrivateMessage.title) {
				aData.PrivateMessage.title = '&ltNo Title&gt';
				$('td:eq(2)',nRow).addClass('grey');
			}

			var actions = '\
				<div class="hidden">\
				<div class="inline">\
				<a href="#" class="privatemessage-read">Read</a> | \
				</div>\
				<div class="send-message inline">\
				<a href="#">Reply</a> | \
				<input type="hidden" class="send-message-name" value="'+ sender +'" />\
				<input type="hidden" class="send-message-id" value="'+ id +'" />\
				</div>\
				<div class="inline">\
				<a href="#" class="privatemessage-delete">Delete</a>\
				</div>\
				</div>\
				';


			$('td:eq(2)',nRow).html(aData.PrivateMessage.title + actions );
			
			var checkbox = '<input type="checkbox" name="selected" value="'+id+'"/>';
			$('td:eq(0)',nRow).html(checkbox);
			
			
			return nRow;
		},
		"sAjaxSource": jsMeta.baseUrl+"/private_messages/fetch_messages/",
		"sAjaxDataProp": "", 
		"aoColumns": [
		              { "mDataProp": "PrivateMessage.id", "bVisible": false}, 
		              { "mDataProp": null, "bSortable": false, "sClass": "checkbox-column"  },		                     
		              { "mDataProp": "UserSender.username" ,"sClass": "message-username"},
		              { "mDataProp": "PrivateMessage.title", "sClass":"no-overflow message-title padding-left-right"},
		              { "mDataProp": "PrivateMessage.created", "bVisible": false},			
		              { "mDataProp": "PrivateMessage.timeago" ,"iDataSort": 4,  "sClass":"center no-overflow"},
		              { "mDataProp": "PrivateMessage.message", "bVisible": false, "bSearchable": true}	
		              ]
	} );

	return oTable;

}
/* Get the rows which are currently selected */
function fnGetSelected( oTableLocal )
{
	var aReturn = new Array();
	var aTrs = oTableLocal.fnGetNodes();
	
	for ( var i=0 ; i<aTrs.length ; i++ )
	{
		if ( $(aTrs[i]).hasClass('row_selected') )
		{
			aReturn.push( aTrs[i] );
		}
	}
	return aReturn;
}


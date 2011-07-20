function PrivateMessageClass(dataTable) {
	this.dataTable = dataTable;
	this.openRows = new Array();
	this.titleRow = null;
	this.titleRowIndex = null;
	this.messageRow = null;
	this.message = null;
	this.messageId = null;
	this.messageDiv = '<div class="the_message hidden"/>';	
	this.className = null;
	this.toClose = null;
	this.isOpen = false;
	this.openRowIndex = null;
	this.clickedElement = null;
	this.data = null;
	this.accordion = true;
	this.deleteCallback = function() {}
	this.setDataTable = function(dataTable){this.dataTable = dataTable;}

	this.isMessageRow = function(tr){
		if($(tr).children().hasClass('message_td')) {
			return true;
		}
		return false;
	}
	
	this.init = function() {
		this.titleRowIndex = this.dataTable.fnGetPosition(this.titleRow);		
		this.data =  this.dataTable.fnGetData(this.titleRowIndex);
		this.openRowIndex = $.inArray(this.titleRow, this.openRows );
		if(this.openRowIndex == -1) {	
			
			this.isOpen = false;
		} else {
			this.isOpen = true;
		}
	}
	
	this.initRead = function() {
		this.className = this.titleRow.className;
		this.message = this.data.PrivateMessage.message;	
	}

	
	this.switchMessage = function() {
		
		var pm = this;
		if (this.isOpen) {
			var theMessageDiv = $('.the_message', $(this.openRows[this.openRowIndex]).next()[0]);
		$(theMessageDiv).slideUp(300,function(){
				pm.close(pm.openRowIndex);
		 });
			
		}else {
		if (this.accordion) {
			
				$('.the_message').slideToggle(300,function(){
					pm.close(0);
					pm.openMessage();
				});
			} else {
				pm.openMessage();
			}
		}
	}
	
	
	this.openMessage = function() {
		this.messageRow = dataTable.fnOpen( this.titleRow,
				nl2br(this.message,false),
				"message_td");
		$('td', this.messageRow).wrapInner(this.messageDiv);
		$('.the_message').slideDown(300);		
		$(this.messageRow).addClass(this.className);
		$(this.messageRow).addClass('message_row');
		$(this.titleRow).addClass('row_expanded');
		$('.message-buttons', this.titleRow).removeClass('hidden');
		this.isOpen = true;
		this.openRows.push(this.titleRow);
	}

	this.close = function(index) {
		this.toClose = $(this.openRows[index]);
		$('.message-buttons', this.toClose).addClass('hidden');
		$(this.toClose[0]).removeClass('row_expanded');
		this.dataTable.fnClose(this.toClose[0]);
		this.openRows.splice(index, 1);
	}

	
	this.deleteMessage = function(titleRow, deleteCallback ) {
		if($(this.titleRow).hasClass('row_expanded')) {this.isOpen = false;}
		this.deleteCallback = deleteCallback;		
		this.dataTable.fnDeleteRow(this.titleRowIndex,
				this.deleteCallback(this.data)
		);
	}

}
function privateMessageInboxInit() {
	var oTable = $('#PrivateMessages-table').dataTable( {
		"sDom": '<"H"lfrT>t<"F"ip>',
		"oTableTools": {			
			"aButtons": [
	             {
		             "sExtends": "text",
		             "sButtonText": "Delete",
	            	 "fnClick": function ( nButton, oConfig, oFlash ) {
	            		 var selected = $('#PrivateMessages-table tbody :checked').parent().parent();
	            		 var total_selected = selected.length;
	            		 if(!total_selected) return;
	            		 var confirm = false;
	            		 var ext = total_selected > 1 ? " messages?" : " message?";
	            		 var deletemessage = "Delete "+total_selected+ext;
	            		 $( '<div id="dialog-confirm" title="'+deletemessage+'"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These messages will be permanently deleted and cannot be recovered. Are you sure?</p></div>' ).dialog({
	            			 resizable: false,
	            			 height:200,
	            			 modal: true,
	            			 buttons: {
	            				 Delete: function() {
	            					 $.each(selected,function(index,tr){
	            						 var pos = oTable.fnGetPosition(tr);
	            						 var id = oTable.fnGetData(pos).PrivateMessage.id;
	            						 oTable.fnDeleteRow(pos, privateMessageDelete(id));
	            					 });
	            					 $( this ).dialog( "close" );
	            				 },
	            				 Cancel: function() {
	            					 $( this ).dialog( "close" );
	            				 }
	            			 }
	            		 });

	            		
	            		
							
						}
	             },
	             
	             {
	            	 "sExtends": "collection",
		             "sButtonText": "Mark as...",
		             "aButtons": [
		             {
		            	 "sExtends": "text",
			             "sButtonText": "Read",
			             "fnClick": function ( nButton, oConfig, oFlash ) {
			            	 
			             }
		             },
		             {
			             "sExtends": "text",
			             "sButtonText": "Unread",
			             "fnClick": function ( nButton, oConfig, oFlash ) {
			            	 
			             }
		             }
		             ]
	             },
	             {
	            	 "sExtends": "text",
		             "sButtonText": "Flag as Inappropriate",
		             "fnClick": function ( nButton, oConfig, oFlash ) {
		            	 
		             }
	             }
        	 ]
		},
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
				<div class="hidden message-buttons">\
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
			var user_div = '<div class="user_div">\
								<span>'+sender+'</span>\
							</div>';
			$('td:eq(1)',nRow).html(user_div);
			$('td:eq(2)',nRow).html(aData.PrivateMessage.title + actions );

			var checkbox = '<input type="checkbox" name="selected" value="'+id+'"/>';
			$('td:eq(0)',nRow).html(checkbox);


			return nRow;
		},
		"sAjaxSource": jsMeta.baseUrl+"/private_messages/fetch_messages/inbox/",
		"sAjaxDataProp": "messages",
		"aoColumns": [
		              {"mDataProp": "PrivateMessage.id", "bVisible": false},
		              {"mDataProp": null, "bSortable": false, "sClass": "checkbox-column"},
		              {"mDataProp": "UserSender.username" ,"sClass": "message-username"},
		              {"mDataProp": "PrivateMessage.title", "sClass":"no-overflow message-title padding-left-right"},
		              {"mDataProp": "PrivateMessage.created", "bVisible": false},
		              {"mDataProp": "PrivateMessage.timeago" ,"iDataSort": 4,  "sClass":"center no-overflow"},
		              {"mDataProp": "PrivateMessage.message", "bVisible": false, "bSearchable": true}
		              ]
	} );
	return oTable;

}

function privateMessageSentInit() {
	var oTable = $('#PrivateMessages-table').dataTable( {
		"oSearch": {"sSearch": ""},
		"aaSorting": [[4,'desc']],
		"bProcessing": true,
		"bJQueryUI": true,
		"bAutoWidth": false,
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

			var id = aData.PrivateMessage.receiver;
			var receiver = aData.UserReceiver.username;
			if  (!aData.PrivateMessage.title) {
				aData.PrivateMessage.title = '&ltNo Title&gt';
				$('td:eq(2)',nRow).addClass('grey');
			}

			var actions = '\
				<div class="hidden message-buttons">\
				<div class="send-message inline">\
				<a href="#">Reply</a> | \
				<input type="hidden" class="send-message-name" value="'+ receiver +'" />\
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
		"sAjaxSource": jsMeta.baseUrl+"/private_messages/fetch_messages/sent",
		"sAjaxDataProp": "messages",
		"aoColumns": [
		              {"mDataProp": "PrivateMessage.id", "bVisible": false},
		              {"mDataProp": null, "bSortable": false, "sClass": "checkbox-column"},
		              {"mDataProp": "UserReceiver.username" ,"sClass": "message-username"},
		              {"mDataProp": "PrivateMessage.title", "sClass":"no-overflow message-title padding-left-right"},
		              {"mDataProp": "PrivateMessage.created", "bVisible": false},
		              {"mDataProp": "PrivateMessage.timeago" ,"iDataSort": 4,  "sClass":"center no-overflow"},
		              {"mDataProp": "PrivateMessage.message", "bVisible": false, "bSearchable": true}
		              ]
	} );
	return oTable;

}


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




$(document).ready(function() {
	var dataTable
	if (page == "sent") {		
		dataTable = privateMessageSentInit();
	} else {
		dataTable = privateMessageInboxInit();
	}
	var PrivateMessage = new PrivateMessageClass(dataTable);
	
	$( "#PrivateMessages-table" ).selectable({ 
				filter: 'tr ', 
				distance: 20,
				selected: function(event, ui) {
					var checkbox = $(ui.selected).children().children()[0];
					if($(checkbox).is(':checked')) {
						$(checkbox).prop('checked', false);
					} else {
						$(checkbox).prop('checked', true);
					}
						
				} });

	$('#PrivateMessages-table > tbody > tr').live("click mouseover mouseout",function(e){

		if(e.type=="mouseover") { //show the links for read/reply/delete
			$('td:eq(2) > div',this).removeClass('hidden');
			return true;
		}

		if(e.type=="mouseout"){ // donot remove links for read/reply/delete if the message is open
			if($(this).next().children().hasClass('message_td')) {
				return true;
			}			
			$('td:eq(2) > div',this).addClass('hidden');//remove links for read/reply/delete on mouseout
			return true;
		}
		
		
		
		
		if(e.type=="click") {
			PrivateMessage.titleRow = this;
			if(PrivateMessage.isMessageRow(this)) {return false;}
			PrivateMessage.init();
			PrivateMessage.clickedElement = e.target; //its the clicked element
			
			
			if(PrivateMessage.clickedElement.className == 'privatemessage-delete') {
				 $( '<div id="dialog-confirm" title="Delete message?"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Delete  permanently?</p></div>' ).dialog({
        			 resizable: false,
        			 height:180,
        			 modal: false,
        			 buttons: {
        				 Delete: function() {
        					 PrivateMessage.deleteMessage(this, function(rowDetails){				
        						 privateMessageDelete(rowDetails.PrivateMessage.id);
        					 });
        					 $( this ).dialog( "close" );
        				 }, 
        				 Cancel: function() {
        					 $( this ).dialog( "close" );
        				 }
        			 }
        		 });
				
				
				return false;
			}
			

			if(PrivateMessage.clickedElement.type == "checkbox") {
				if($(PrivateMessage.clickedElement).is(':checked')) {
					$(PrivateMessage.titleRow).addClass('row_selected');					
				} else {
					$('#PrivateMessages-table > thead input, #PrivateMessages-table > tfoot input').prop('checked', false);
					$(PrivateMessage.titleRow).removeClass('row_selected');
				}
				return true;
			}
			
			
			PrivateMessage.initRead();

			if(PrivateMessage.openRows.length) {
				PrivateMessage.switchMessage();
			} else {
				PrivateMessage.openMessage();
			}


			
			
		}
	});
	
	$('#PrivateMessages-table > thead input, #PrivateMessages-table > tfoot input').live("change",function(e){
		var checkboxes = $('#PrivateMessages-table > tbody > tr > td.checkbox-column input');
		if($(this).is(':checked')) {
			$('#PrivateMessages-table > thead input, #PrivateMessages-table > tfoot input').prop('checked', true);
			checkboxes.prop('checked', true);
		} else {
			$('#PrivateMessages-table > thead input, #PrivateMessages-table > tfoot input').prop('checked', false);
			checkboxes.prop('checked', false);
		}
	});


} );







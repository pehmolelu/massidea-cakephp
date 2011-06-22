$(document).ready(function(){
	$("#accordion").accordion({header:'div.message-header', active:'none', autoHeight:false,collapsible:true});
	$(".confirm-delete").hide();
		$(".message-header >div> img.send").click(function() {
		var id = $(this).siblings('.send-message-id');
		if($(this).hasClass('delete'))

			return false;
		var name = $(this).siblings('.send-message-name');
		$("#PrivateMessageTo").text(name.val());
		$("#PrivateMessageReceiver").val(id.val());
		$("#send_private_message").dialog("open");
		return false;
	});
	$(".message-header >div> img.delete").click(function() {
		$(this).hide();
		$(this).siblings('.send').hide();
		$(this).siblings('.confirm-delete').show();
		return false;
	});

});
kis.sms = {
    
    messages_init: function(){
	$(function(){
   
	    $('#status_option .select_all').click(function(){
		$('#status_option input[name="status"]').prop('checked', true);
	    });
	    
	    $('#status_option .unselect_all').click(function(){
		$('#status_option input[name="status"]').prop('checked', false);
	    });
	});
    },
    messagetemplates_init: function(lang){
	$(function(){
   
	    $('.edit_table_list .table_row_tool .delete_dim').click(function(){
		
		if (confirm(lang.message)){
		    $.post('./apps/sms/ajax.php?action=removetemplate', kis.loadBoard); 
		}
		
		return false;
	    });
	    
	});
	
    },
    message_new_init: function(){
	$(function(){
	    $('#message').on('keypress keyup', function(){
		 $('input[name="message_size"]').val(160-$(this).val().length);//char count
	     }); 
	     
	 });
    }
    
}
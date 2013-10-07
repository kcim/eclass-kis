kis.calendar = {
    
    calendar_menu_init: function(lang){

	    
	$('.calendar_event_list_detail_edit .delete_dim').click(function(e){
	    
	    var event_id = $(this).parent().siblings('.event_id').html();
	    var event_title = $(this).parent().siblings('.event_title').html();
		    
	    if (confirm(lang.remove+' "'+event_title+'"?')){
		$.post('apps/calendar/ajax.php?action=removeEvent', {event_id: event_id});
	    }
	    return false;
	    
	});

    },
    monthly_init: function(date){
	
    
	$('.btn_previous').click(function(){
	    $.address.path('/apps/calendar/'+date.previous+'/');
	});
	$('.btn_next').click(function(){
	    $.address.path('/apps/calendar/'+date.next+'/');
	});
		    
	
	
    },
    yearly_init: function(options){

	

	$('.show_month').change(function(){
	    if ($(this).val()=='coming'){
		
		$('.calendar_hidden').hide();
		
	    }else{
		$('.calendar_hidden').fadeIn();
	    }
	});
	
	$('.calendar_table').click(function(){
	    
	    var month = $('.calendar_table').index(this)+1;
	   
	    $('.calendar_event_list_detail li').hide();
	    $('.calendar_events_month_'+month).fadeIn();
	    $('.month_tab').attr('href', "#/apps/calendar/"+options.academic_year_id+"/"+month+"/");
	    
	    return true;
	    
	});
	
	$('.calendar_table:visible:first').click();

    }
        
}
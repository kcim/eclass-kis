kis.eattendance = {
    
    monthlyrecord_parent_init: function(lang){

	 kis.setNavigationItems([lang.monthdetails]);

    },
    monthlyrecord_form_init: function(lang){
	
	$('#status5, #status4').change(function(){
	    
	    if ($('#status5').is(':checked')){
		$('#OptionsLayer').fadeIn();
	    }else{
		$('#OptionsLayer').hide();
	    }
	});
	
	$('.formsubbutton').click(function(){
	    $('#status5').click();
	    return true;
	});
	
	$('.main_content_detail form').submit(function(){
	    
	    if (!/^[0-9]+$/.test($('#textfield4').val())){
		alert(lang.pleaseentervalid+lang.displayinred);
		$('#textfield4').focus();
		return false;
	    }
	   
	    return true; 
	});
	
    },
    remainderrecord_init: function(lang){

	$('.edit_table_list').selectable({
	    filter: '.edit_table_row',
	    cancel: 'a'
	});

	$('.edit_table_list .table_row_tool .delete_dim').click(function(){
	    
	    var reminder_id = $(this).find('.reminder_id').val();
	    
	    if (confirm(lang.areyousuretoremovethisrecord)){
		$.post('./apps/eattendance/ajax.php?action=removereminderrecord',{reminder_id: reminder_id}, function(){
		    kis.loadBoard()
		});
	    }
	    
	    return false;
	});	    
	
    },
    reminderrecord_edit_init: function(){
	
	kis.datepicker('input[name="date"]');
		
	$('.reminder_edit_form').submit(function(){
	    
	    $(this).find('.formbutton').prop('disabled', true);
	    $.post('./apps/eattendance/ajax.php?action=updatereminderrecord', $(this).serialize(), function(){
		$.address.value('/apps/eattendance/reminderrecord');
	    });
	    
	    return false;	
	    
	});
	$('.formsubbutton').click(function(){
	    $.address.value('/apps/eattendance/reminderrecord');
	});
	
    },
    reminderrecord_create_init: function(lang){
	
	kis.datepicker('input[name="date"]');
	
	kis.datepicker('input[name="start_date"]').datepicker("option", "onClose", function(selectedDate){
	    $('input[name="end_date"]').datepicker("option", "minDate", selectedDate);
	});
	kis.datepicker('input[name="end_date"]').datepicker("option", "onClose", function(selectedDate){
	    $('input[name="start_date"]').datepicker("option", "maxDate", selectedDate);
	});
	
	$('select[name="type"]').change(function(){
	    
	    switch ($(this).val()){
		
		case 'single':
		    
		    $('.row_date').fadeIn();
		    $('.row_startdate, .row_enddate, .row_weekdays').hide();
		    
		break;
		
		case 'daily':
		    
		    $('.row_startdate, .row_enddate').fadeIn();
		    $('.row_date, .row_weekdays').hide();
		    
		break;
	    
		case 'weekly':
		    
		    $('.row_startdate, .row_enddate, .row_weekdays').fadeIn();
		    $('.row_date').hide();

		break;
		
	    }
	    
	});
	
	$('.reminder_create_form').submit(function(){
	    
	    if ($('select[name="teacher"]').val()==''){
		alert(lang.pleaseentervalid+lang.correspondingteacher);
		return false;
	    }else if ($('.notice_type_select input[name="students[]"]').length==0){
		alert(lang.pleaseentervalid+lang.students);
		return false;
	    }
	    
	    $(this).find('.formbutton').prop('disabled', true);
	    $.post('./apps/eattendance/ajax.php?action=createreminderrecords', $(this).serialize(), function(){
		$.address.value('/apps/eattendance/reminderrecord');
	    });
	    
	    return false;	
	    
	});
	
	$('.search_user_class').change(function(){
	    
	   var class_id = $('.search_user_class').val();
	  
	   if (class_id==''){ return; }
	   
	   $.post('./ajax.php?action=searchuser', {
		class_id: class_id,
		user_type: 'student',
		excludes: $('.notice_type_select .user .id').map(function(){return $(this).val()}).get()
	    }, function(res){
		    
		if (res.error){
		    $('.notice_type_search .search_list').html(res.error);
		    
		}else{
		    
		    $('.notice_type_search .search_list').html('');
		    
		    $.each(res.users, function(i, user){
			
			var li = $('#search_user_template li').clone();
			
			li.find('span').html(user['user_name_'+kis.lang]);
			li.find('.id').val(user.user_id);
			
			li.attr('title', user['user_name_'+kis.lang]+' ('+user.user_class_name+' '+user.user_class_number+')');
		       
			$('.notice_type_search .search_list').append(li);
			
		    });
		    
		}
	    }, 'json');
	    
	   
	});
	
	$('.search_list').on('click', 'li .add',function(){
	    
	    $(this).siblings('.id').prop('disabled', false);
	    $('.notice_type_select .search_list').append($(this).closest('li'));
	    return false;    
	    
	});
	
	$('.search_list').on('click', 'li .delete',function(){
	    
	    var li = $(this).closest('li');
	    
	    $(this).siblings('.id').prop('disabled', true);
	    $('.notice_type_search .search_list').prepend(li);
	    return false;    
	    
	});
	
	$('.formsubbutton').click(function(){
	    $.address.value('/apps/eattendance/reminderrecord');
	});
	
    },
    takeattendance_classes_init: function(){
    
	$('.attendance_class_list').masonry();
	kis.datepicker("input[name='date']").datepicker("option", "maxDate", 0);
	
    },
    takeattendance_students_init: function(options, lang){
	
	kis.datepicker('input[name="date"]').datepicker("option", "maxDate", 0);
	
	$('.table_board .tool_edit').click(function(){
	    
	    $('.edit, .view').toggle(); 
	    
	    return false;
	});
	$('.table_board .tool_set').click(function(){
	    
	    $('.table_board .status').filter(function(){return $(this).val()=='absent';}).val('present').change();
	    
	    return false;
	});
		
	
	$('.table_board select, .reason, .remark').change(function(){
	    
	    var tr = $(this).closest('tr');
	    
	    if ($(this).is('.status')){
		
		var status = $(this).val();
		tr.attr('class', 'attendance'+status);

		if (status=='absent' || status=='outing'){
		    tr.find('.absent_show, .present_hide').show();
		    tr.find('.absent_hide').hide();
		}else if (status=='present'){
		    tr.find('.absent_show, .present_hide').hide();
		    tr.find('.absent_hide').show();
		}else{
		    tr.find('.absent_show').hide();
		    tr.find('.absent_hide, .present_hide').show();
		}
	    }
	    
	    var params = {
		apm: options.apm,
		student_id: tr.find('.student_id').val(),
		date: options.date,
		status: tr.find('.status').val(),
		in_school_time: tr.find('.in_school_hour').val()+':'+tr.find('.in_school_min').val()+':00',
		leave_school_time: tr.find('.leave_school_hour').val()+':'+tr.find('.leave_school_min').val()+':00',
		reason: tr.find('.reason').val(),
		remark: tr.find('.remark').val()
	    };

	    tr.find('.status_view').html(tr.find('.status option:selected').html());
	    tr.find('.in_school_time_view').html(params.status!='absent'&&params.status!='outing'?params.in_school_time:'--');
	    tr.find('.leave_school_time_view').html(params.status!='absent'&&params.status!='outing'?params.leave_school_time:'--');
	    
	    $.post('./apps/eattendance/ajax.php?action=setattendance', params, function(res){
		
		tr.find('.date_time').show();
		tr.find('.modified_view').html(res.modified);
		tr.find('.modified_by_view').html(res.modified_by);
		tr.find('.reason_view').html(params.status=='present'?'--':res.reason);
		tr.find('.remark_view').html(res.remark);
		
	    }, 'json');
	    
	});
	
    }
}
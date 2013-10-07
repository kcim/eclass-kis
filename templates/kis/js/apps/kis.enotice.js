kis.enotice = {
    
    notices_teacher_init: function(lang){

    
	$('.edit_table_list .table_row_tool .delete_dim').click(function(){
	    
	    var notice_id = $(this).find('.notice_id').val();
	    
	    if (confirm(lang.message+'?')){
		kis.showLoading();
		$.post('./apps/enotice/ajax.php?action=removenotice', {notice_id: notice_id}, function(){
		    kis.hideLoading().loadBoard();
		});
    
	    }

	    
	    return false;
	    
	});
    },
    notice_result_stat_init: function(){
	
	kis.setNavigationItems([$('.notice_title').html()]);
	
    },
    notice_result_class_init: function(){
	
	kis.setNavigationItems([$('.notice_title').html()]);
	
    },
    notice_detail_teacher_init: function(){
	
	kis.setNavigationItems([$('.notice_title').html()]);
	
    },
    notice_form_init: function(options, lang){
    
	var upload_file_count = 0;
	var current_questions = [];
	
	kis.setNavigationItems([$('.notice_title').html()]);
	
	kis.uploader({
	    browse_button:  'uploader_button',
	    url: './apps/enotice/ajax.php?action=addfile&notice_id='+options.notice_id,
	    auto_start: true,
	    onFilesAdded: function(up, files) {
		
		kis.lock(lang.uploadfileconfirm);
		upload_file_count++;
		var uploader = this;
			
		$.each(files, function(i, file){
		    
		    var button = $('<a class="btn_attachment">').html(file.name);
		    
		    $('.attachment_list').append($('<div class="attachment">').attr('id', file.id).append(button).append('<em class="progress">'));
		   
		});
	    
	    },
	    onUploadProgress: function(up, file) {
		$('#'+file.id+' .progress').html(''+file.percent+'%');
	    },
	    onFileUploaded: function(up, file, info) {
		
		var res = $.parseJSON(info.response);
		    
		$('#'+file.id+' .btn_attachment').attr('href', './apps/enotice/ajax.php?action=getfile&folder='+res.folder+'&file_name='+res.file_name).html(res.file_name);
		$('#'+file.id+' .progress').replaceWith($('<a class="btn_remove" href="#">'));
		$('#'+file.id).append('<input name="new_files[]" value="'+res.file_url+'" type="hidden"/>');
	    
		if (--uploading_files_count == 0){
		    kis.unlock();
		}
	    
	    }
	});
	
	kis.datepicker('input[name="issue_date"]').datepicker("option", "onClose", function(selectedDate){
	    $("input[name='due_date']").datepicker("option", "minDate", selectedDate);
	});
	kis.datepicker('input[name="due_date"]').datepicker("option", "onClose", function(selectedDate){
	    $("input[name='issue_date']").datepicker("option", "maxDate", selectedDate);
	});
	
	var editor = kis.editor('editor_content');
	
	var reorderQuestionNum = function(){
	    	  
	    if ($('.notice_paper .notice_question').length==0){
		$('.notice_paper .no_record').show();
	    }else{
		$('.notice_paper .no_record').hide();
		$('.notice_paper .notice_question').each(function(i){
		    $(this).find('strong').html(i+1);
		});
	    }

	};
	
	var validateDates = function(issue, due){
	    
	    try{
		
		issue = $.datepicker.parseDate("yy-mm-dd", issue);
		due = $.datepicker.parseDate("yy-mm-dd", due);
		
		if (due.getTime() <= issue.getTime()){
		    return false;
		}
		
	    }catch(e){
		
		return false;
	    }
	    
	    return true;
	};
	
	$('input[name="number"]').on('keyup',function(e){
	    
	    var notice_number = $(this).val();
	    $.get('./apps/enotice/ajax.php?action=checknoticenumber', {notice_number: notice_number}, function(res){
	       
		if (res.notice_number == notice_number){
		    if (res.available){
			$('.notice_number_yes').show().find('em').html(notice_number);
			$('.notice_number_no').hide();
		    }else{
			$('.notice_number_no').show().find('em').html(notice_number);
			$('.notice_number_yes').hide()
		    }
		   
		}
    
	    }, 'json');
	    
	});
	
	
	$('input[name="status"]').change(function(){
	   
	    if ($('#status_2').is(':checked')){
		$('#status_2').siblings('.tabletextrequire').show();
		$('#status_1').siblings('span').find('input').prop('disabled', true);
	    }else{
		$('#status_2').siblings('.tabletextrequire').hide();
		$('#status_1').siblings('span').find('input').prop('disabled', false);
		
	    }
	});
	
	
	$('.choose_template').change(function(){
	    
	    var notice_id = $(this).val();
	    var notice_title = $(this).find('option:selected').html();
	    
	    if (notice_id!='' && confirm(lang.loadfromtemplate+' "'+notice_title+'"?')){
		$.post('./apps/enotice/ajax.php?action=gettemplate', {notice_id: notice_id}, function(res){
		    $('input[name="title"]').val(res.title);
		    $('input[name="answer_all"]').prop('checked', res.answer_all==1);
		    $('input[name="display_number"]').prop('checked', res.display_number==1);
		    current_questions = res.questions;
		    editor.html(res.description);
		    
		    $('.notice_paper .notice_question').remove();
		    
		    res.questions.length>0? $('.notice_paper .no_record').hide(): $('.notice_paper .no_record').show();
		    
		    $.each(res.questions_ui, function(i, ui){
			
			var template = $('#notice_question_template .'+res.questions[i].type).clone().show();
			template.find('.notice_question_container').html(ui);
			$('.notice_paper').append(template);
			
		    });
		    
		
		}, 'json');
	    }else{
		$(this).val('');
	    }
	    
	});
	
	
	$('.notice_type_tab a').click(function(){
	    
	    var level_id = $(this).find('.type_id').val();
	   
	    $('.notice_type').hide();
	    $('#notice_type_'+level_id).show();
	    $(this).addClass('selected').siblings().removeClass('selected');
	    $('input[name="type"]').val(level_id);
	    
	    return false;
	    
	});
	    
	$('.notice_preview_reply').click(function(){
	    
	    var show_question_num = $('#display_number').is(':checked');
	  
	    $('#notice_question_preview .notice_paper_content_bg').html(
		$('.notice_paper .notice_question_container').map(function(i){
		    
		    var question = $(this).clone();
		    question.find('.delete').hide();
		    question.find('span.content').html(function(){
			return $(this).siblings('.input:text').val();    
		    });
		    
		    var question_num = show_question_num? (i+1)+'.': '';
		    return '<div class="notice_reply">'+question_num+question.html()+'</div>';
		    
		}).get().join()
	    );
	    
	    $.fancybox.open($('#notice_question_preview'),{margin:0,padding:0});
	    
	  
	});
	
	$('.notice_type_user_search').click(function(){
	    
	    var group_id = $('.search_group').val();
	    var name = $('.search_name').val();
	    
	    if (group_id!=''||$.trim(name)!=''){
		$.get('./apps/enotice/ajax.php?action=searchgroupuser', {group_id: group_id, name: name}, function(){
		});
	    }
	     
	    return false;
	});
	
	$('.notice_form').submit(function(){
	    
	    if (kis.hasEmptyInputElements($('input[name="number"]'))){
		
		alert(lang.pleaseentervalid+lang.noticenumber);
		return false;
		
	    }else if (kis.hasEmptyInputElements($('input[name="title"]'))){
		
		alert(lang.pleaseentervalid+lang.title);
		return false;
		
	    }else if (!validateDates($('input[name="issue_date"]').val(),$('input[name="due_date"]').val())){
		
		alert(lang.pleaseentervalid+lang.date);
		return false;
	    }else if (kis.hasEmptyInputElements($('.notice_question_container :text:visible').not(':disabled'))){
		
		alert(lang.pleaseentervalid+lang.replyslip);
		return false;
	    }
	    
	    editor.finish();
	    kis.showLoading();
	    $.post('./apps/enotice/ajax.php?action=setnotice',$(this).serialize(),function(res){
		$.address.value('/apps/enotice/noticelist/'+res.notice_id);
		kis.hideLoading();
	    
	    },'json');
	    $('.edit_bottom .formbutton').prop('disabled', true);
	   
	    
	   return false;
	});
	
	$('.notice_questions_choose a').click(function(){
	    var type = $(this).find('.type').val();
	    var template = $('#notice_question_template .'+type).clone().fadeIn();
	    	    
	    $('.notice_paper .no_record').hide();
	    $('.notice_paper').append(template);
	    
	    reorderQuestionNum();
	    
	    return false;
	});
	
	$('.notice_paper').on('click', '.add_choice_multiple',function(){
	   
	    var question = $(this).prev().clone();
	    question.find('input[name="question_choices[]"]').val('');
	    question.find('.table_row_tool').show();
	    
	    $(this).before(question);
	    $(this).siblings('input[name="question_choice_count[]"]').val(function(i,v){return parseInt(v)+1;});
	 
	    return false;
	    
	});
	
	$('.notice_paper').on('click', '.add_choice_single',function(){
	   
	    var question = $(this).prev().clone();
	    question.find('input[name="question_choices[]"]').val('');
	    question.find('.table_row_tool').show();
	    
	    $(this).before(question);
	    if ($(this).siblings('input[name="question_choice_count[]"]').val(function(i,v){return parseInt(v)+1;}).val()==options.max_options){
		$(this).hide();
	    }
    
	 
	    return false;
	});
	
	$('.notice_paper').on('click', '.delete_dim',function(){
	   
	    $(this).closest('.notice_question').fadeOut(function(){
		$(this).remove();
		reorderQuestionNum();
	    });
	    	    
	    return false;
	
	});
	
	$('.notice_paper').on('click', '.notice_question_container .delete',function(){

	    $(this).parent().parent().siblings('input[name="question_choice_count[]"]').val(function(i,v){return parseInt(v)-1;});
	    $(this).parent().parent().remove();
		    
	    return false;
	});
	
	$('.notice_paper').sortable({items: '.notice_question',update: reorderQuestionNum});
	
	$('.attachment_list').on('click', '.btn_remove', function(){
	    
	    var target_file = $(this).siblings('.btn_attachment');
	    var file_name = target_file.html();
	    var file_url = target_file.attr('href');
	    
	    if (confirm(lang.areyousureto+lang.remove+' "'+file_name+'"?')){
		
		$(this).parent().remove();
		$('.notice_form').append('<input type="hidden" name="removed_files[]" value="'+file_url+'"/>');
				
	    }
	    return false;
	    
	});
	
	$('.notice_form .formsubbutton').click(function(){
	    
	    $.post('./apps/enotice/ajax.php?action=removefiles',{
		notice_id: options.notice_id,
		files: $('.attachment input[name="new_files[]"]').map(function(){return $(this).val()}).get()
	    });

	    $.address.value(options.notice? '/apps/enotice/noticelist/'+options.notice: '/apps/enotice/');
	});
	
	$('.notice_type .search_list').on('click', 'li .add',function(){
	    
	    $(this).siblings('.id').prop('disabled', false);
	    $('.notice_type_select .search_list').append($(this).closest('li'));
	    return false;    
	    
	});
	
	$('.notice_type .search_list').on('click', 'li .delete',function(){
	    
	    var li = $(this).closest('li');
	    
	    $(this).siblings('.id').prop('disabled', true);
	    $('.notice_type_search ul.search_'+li.attr('class')).prepend(li);
	    return false;    
	    
	});
	
	$('.notice_type_search .search input').keypress(function(e){
	    
	    var search = $(this).val();
	    if(search!='' && e.keyCode==13){
		
		$.post('./ajax.php?action=searchuser',{
		    type: 'student',
		    search: search,
		    excludes: $('.notice_type_select .user .id').map(function(){return $(this).val()}).get()
		}, function(res){
		    
		    if (res.error){
			$('.notice_type_search .search_user').html(res.error);
		        
		    }else{
			
			$('.notice_type_search .search_user').html('');
			$.each(res.users, function(i, user){
			    
			    var li = $('#search_user_template li').clone();
			    li.find('span').html(user['user_name_'+kis.lang]);
			    li.find('.id').val(user.user_id);
			    
			    li.attr('title', user['user_name_'+kis.lang]+' ('+user.user_class_name+' '+user.user_class_number+')');
			   
			    $('.notice_type_search .search_user').append(li);
			    
			});
			
		    }
		}, 'json');
		return false;
	    }
	    return true;
	    
	});

	
    },
    notice_detail_parent_init: function(options, lang){
    
	$('#module_page').addClass('wood');
	kis.setNavigationItems([$('.notice_title').html()]);
	
	$('#notice_reply_form').submit(function(){
	    
	    var params = $(this).serializeArray();
	    var form_error = '';

	    if (options.answer_all){
		
		if (params.length < options.questions_count){
		    form_error = lang.pleaseanswerallquestions+'!';
		}else{
		    $.each(params, function(){
			if (this.value==''){
			    form_error = lang.pleaseanswerallquestions+'!';
			    return false;
			}
			return null;
		    });
		}
		
	    }

	    if (form_error!=''){
		alert(form_error);
		return false;
	    }
	    if (confirm(lang.signnotice+'?')){
		
		kis.showLoading();
		$.post('./apps/enotice/ajax.php?action=submitreply&notice_id='+options.notice_id, params, function(res){
		    
		    kis.hideLoading();
		    $('.notice_signed').hide().fadeIn().html(res.modified+' '+lang.signedby+' '+res.signed_by);
		    
		    if (options.hide_button_after_submit){
			$('#sign_notice').hide();
		    }

		},'json');
	    }
    
	    return false;
	    
	});
	
	$('#print_notice').click(function(){
	    
	    window.open('./apps/enotice/ajax.php?action=getprint&student_id='+options.student_id+'&notice_id='+options.notice_id);
	});
	   

	
    }
    
 
}
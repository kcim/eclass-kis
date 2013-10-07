kis.message = {

    getCheckedMails: function(){
	
	return $('.checkbox_item:checked').map(function(){
		    return $(this).val();
		}).get();
    },
    

    moveMail: function(mails, folder_id, callback){
	
	if (mails.length>0 && folder_id!=""){
		
	    $.post('apps/message/ajax.php?action=movemails', {mail_ids: mails, folder_id: folder_id}, callback, 'json');
	    
	}
	
    },
    
    markMail: function(mails, markas, callback){
	
	if (mails.length>0 && markas!=""){
		
	    $.post('apps/message/ajax.php?action=markmails', {mail_ids: mails, markas: markas}, callback, 'json');
	    
	}
	
    },
    
    removeMail: function(mails, callback){
	
	if (mails.length>0){
		
	    $.post('apps/message/ajax.php?action=removemails', {mail_ids: mails}, callback, 'json');
	    
	}
	
    },
    
    mail_menu_init: function(force_refresh){
	
	$('.mail_menu .mail_refresh').click(function(){
	    if (force_refresh){
		kis.loadBoard();
	    }
	});
	
    },
    
    mail_detail_init: function(options, lang){
	
	var that = this;
	var mails = [options.mail_id];
	var subject = $('.mail_detail_title h1').text();
	
	kis.setNavigationItems([subject]);
	
	$('.mail_tool .btn_trash').click(function(){
	    	    			
	    if (confirm(lang.areyousuretodelete)){
		
		var callback = function(){
		    $.address.value('/apps/message/'+options.folder+'/');  
		};
		
		if (options.folder=='trash'){
		    that.removeMail(mails, "");
		}else{
		    that.moveMail(mails, '-1', callback);
		}
		
	    }

	    return false;
	    
	});
	
	$('.mail_moveto').change(function(){
	    
	    target_folder = $(this).val();
	    
	    $.address.value('/apps/message/'+options.folder+'/'); 
	    that.moveMail(mails, target_folder);
	});
	
	$('.mail_icon_form .btn_restore').click(function(){
	    
	    $.address.value('/apps/message/trash/'); 
	    that.moveMail(mails, options.mail_real_folder);
	    
	    return false;
	    
	});
	
	$('.mail_reply_form').submit(function(){
	    
	    $(this).find('textarea').prop('readonly', true);
	    $(this).find('.edit_bottom').hide();
	    $.post('apps/message/ajax.php?action=submitreply', $(this).serialize());
	    
	    return false;
	});
	
	$('.mail_recipients_more_btn').click(function(){
	   
	    $(this).siblings('.mail_recipients_more').toggle();
	    
	    return false;
	    
	});
    	
    },
    mail_list_init: function(options, lang){
	
	var that = this;
	
	$('.mail_search_advanced_form .formbutton').click(function(){
	   
	    var form = $(this).closest('.filter_form');
	    form.find('input[name="is_advanced_search"]').val('1');
	    form.find('input[name="search"]').val('');
	    form.submit();
	    
	});
	
	kis.datepicker('input[name="received_from"]').datepicker("option", "onClose", function(selectedDate){
	    $("input[name='received_to']").datepicker("option", "minDate", selectedDate);
	});
	kis.datepicker('input[name="received_to"]').datepicker("option", "onClose", function(selectedDate){
	    $("input[name='received_from']").datepicker("option", "maxDate", selectedDate);
	});
	
	$('.checkbox_check_all, .checkbox_item').click(function(){
	    var target = $(this).is('.checkbox_check_all')? $(this): $('.checkbox_item');
	    
	    if (target.is(':checked')){
		$('.mail_tool, #table_filter').fadeIn(200);
	    }else{
		$('.mail_tool, #table_filter').fadeOut(200);
	    }
	})
	
	$('.mail_tool .btn_trash').click(function(){
	    	    
	    var mails = that.getCheckedMails();
	    if (mails.length>0){
				
		if (confirm(lang.areyousuretodelete+mails.length+lang.items+'?')){
		    
		    var callback = function(){
			kis.loadBoard();
			kis.hideLoading();
		    };
		    kis.showLoading();
		    if (options.folder=='trash'){
			that.removeMail(mails, callback);
		    }else{
			that.moveMail(mails, '-1', callback);
		    }
	    
		}
		  
	    }
	    return false;
	    
	});
	
	$('.mail_moveto').change(function(){
	    
	    var mails = that.getCheckedMails();
	    if (mails != ""){
	    	var folder_id = $(this).val();
	    
	    	$(this).val('');
	    	kis.showLoading();
	    	that.moveMail(mails, folder_id, function(){
	 		   kis.hideLoading();
	 		   kis.loadBoard();	
	    	});
	    }
	});
	
	$('.mail_markas').change(function(){
	    
	    var mails = that.getCheckedMails();
	    if (mails != ""){
	    	var markas = $(this).val();
	    
	    	$(this).val('');
	    	$.each(mails, function(i, mail){
		
	    		if (markas == 1){
	    			$('#mail_row_'+mail).removeClass('mail_unread');
	    		}else{
	    			$('#mail_row_'+mail).addClass('mail_unread');
	    		}
		
	    	});
	    	kis.showLoading();
	    	that.markMail(mails, markas, function(res){
	    		kis.hideLoading();
	    		$('.mail_menu .mail_inbox span').html(res.inbox_count!=0?'('+res.inbox_count+')':'');
	    		$('.mail_menu .mail_draft span').html(res.draft_count!=0?'('+res.draft_count+')':'');
	    	});
	    }
	});
	
	$('.mail_search_advanced').click(function(){
	    
	    $('.mail_search_advanced_form').slideToggle('fast');
	});
	
    },
    settings_init: function(){
	
	var editor = kis.editor('editor_content');
	
	$('#mail_settings').submit(function(){
	    
	    editor.finish();
	    kis.showLoading();
	    $.post('apps/message/ajax.php?action=setsettings', $(this).serialize(), function(){
		kis.hideLoading();
		location.reload();
	    });
	    
	    return false;
	})

    },

    compose_init: function(options, lang){

	var that = this;
	var auto_save;
	var uploading_files_count = 0;
	var used_quota = parseFloat(options.used_quota);
	var total_quota = parseFloat(options.total_quota);
	var subject = $('.mail_title').val();

	if (subject != ''){
	    kis.setNavigationItems([subject]);
	}

	var resetAutoSave = function(){
	    
	    kis.lock(lang.uploadfileconfirm);
	    clearTimeout(auto_save);
	    auto_save = setTimeout(saveDraft, 3000);
	};
	
	var saveDraft = function(callback){
	    
	    editor.finish();
	    
	    $.post('apps/message/ajax.php?action=savedraft', $('.mail_compose_form').serialize(), function(res){
				
		$('.mail_list_filter .btn_trash').show();

		kis.setNavigationItems([$('.mail_compose_form input[name="subject"]').val()]);
		$('.mail_compose_form input[name="mail_id"]' ).val(res.mail_id);
		$('.mail_save_time').html(lang.draftsavedat+': '+new Date().toTimeString().split(' ')[0]);
		$('.mail_save_draft').prop('disabled', false);
		$('.mail_menu .mail_draft span').html('('+res.draft_count+')');
		
		kis.unlock();
		
		if (typeof(callback)=='function') callback();
		
	    },'json');
	};
	
	var setQuota = function(size){
	    
	    used_quota+=size/1024/1024;
	    var used_percent = Math.round(used_quota/total_quota*100)/100;
	    $('.mail_usage_text em').html(Math.round(used_quota*100)/100);
	    $('.mail_usage_bar span').css({right: 100-used_percent+'%'});
	    $('.mail_usage_bar em').html(used_percent+'%');
	    
	};
	

	kis.uploader({
	    browse_button:  'mail_attach',
	    url: './apps/message/ajax.php?action=attachfile',
	    auto_start: false,
	    onFilesAdded: function(up, files){
		
		var uploader = this;

		uploading_files_count ++;
		$.each(files, function(i, file){
		    
		    var button = $('<a class="btn_attachment">').html(file.name);
		    
		    $('.attachment_list').append($('<div class="attachment">').attr('id', file.id).append(button).append('<em class="progress">'));
		   
		});
		
		saveDraft(function(){
		    
		    kis.lock(lang.uploadfileconfirm);
		    //pick up new mail_id
		    uploader.settings.multipart_params= {mail_id: $('.mail_compose_form input[name="mail_id"]' ).val()};
		    uploader.start();
		});
		
	    },
	    onUploadProgress: function(up, file){
		
		$('#'+file.id+' .progress').html(''+file.percent+'%');
		
	    },
	    onFileUploaded: function(up, file, info){
		
		var res = $.parseJSON(info.response);
		
		if (res.error){
		    alert(lang.attachmentsizeexceed);
		    $('#'+file.id).remove();
		}else{
		    
		    var remove_btn = $('<a class="btn_remove" href="">').attr('id', 'remove_'+res.file_id).html($('<span>').addClass('file_size').html(file.size).hide());
		    
		    $('#'+file.id+' .btn_attachment').attr('href', 'apps/message/ajax.php?action=getfile&file_id='+res.file_id).html(res.file_name)
		    $('#'+file.id+' .progress').replaceWith(remove_btn);
		    setQuota(file.size);
		   
		}
		
		if (--uploading_files_count == 0){
		    kis.unlock();
		}
		
	    }
	});
	
	
	var editor = kis.editor('editor_content').keypress(resetAutoSave);
	$('.mail_compose_form :input').on('keypress', resetAutoSave);
	
	$('.attachment_list').on('click', '.btn_remove', function(){
	    
	    var mail_id = $('.mail_compose_form input[name="mail_id"]' ).val();
	    var file_id = $(this).attr('id').replace('remove_','');
	    
	    setQuota(-1*$(this).find('.file_size').html());
	 	  
	    $(this).parent().remove();
	    $.post('apps/message/ajax.php?action=removefile',{mail_id: mail_id, file_id: file_id});
	    
	    return false;
	    
	});
	
	$('.mail_tool .btn_trash').click(function(){
	    	
	    if (confirm(lang.areyousuretodelete)){
	
		var mail_id = $('.mail_compose_form input[name="mail_id"]' ).val();
		kis.showLoading();
		that.moveMail([mail_id], '-1', function(){
		    kis.hideLoading();
		    history.go(-1);
		});
	    }
	    return false;
	    
	});
	
	$('.mail_save_draft').click(function(){
	    
	    $(this).prop('disabled', true);
	    clearTimeout(auto_save);
	    saveDraft();
	});
	
	$('.add_cc').click(function(){
	    $('.cc_recipients').fadeIn();
	    $(this).hide();
	    return false;
	});
	$('.add_bcc').click(function(){
	    $('.bcc_recipients').fadeIn();
	    $(this).hide();
	    return false;
	});
	
	$('.mail_list_filter .formbutton').click(function(){
	    
	    clearTimeout(auto_save);
	    
	    var subject = $('.mail_compose_form input[name="subject"]' ).val();
	    var recipients = $('.mail_compose_form .mail_user' );
	    
	    if (recipients.length==0){
		alert(lang.pleaseselectrecipients);
		return;
	    }else if (subject==''){
		if (!confirm(lang.sendmailwithoutsubject)){
		    return;
		}
	    }

	    saveDraft(function(){
		
		kis.lock(lang.uploadfileconfirm);
		$('.mail_save_time').html(lang.sendingmail+'...');

		var mail_id = $('.mail_compose_form input[name="mail_id"]' ).val();
		kis.showLoading();
		$.post('apps/message/ajax.php?action=sendmail', {mail_id: mail_id}, function(){
		    kis.hideLoading();
		    kis.unlock();
		    $.address.value('/apps/message/sent/'+mail_id+'/?mail_sent=1'); 
		});
	    })
	    
	});
	
	$('.mail_select_user').submit(function(){
	    
	    var form = this;
	    var exclude_list = $('.mail_to_list .mail_user input').map(function(){
		return $(this).val()
	    }).get().join(',');
	    $('.mail_select_user input[name="exclude_list"]').val(exclude_list);
	    
	    kis.showLoading();
	    $.post('apps/message/ajax.php?action=searchusers', $(this).serialize(), function(res){
		kis.hideLoading();
		if (res.count<=0 || res.count>500){
		    $('.mail_select_user .mail_select_all').hide();

		}else{
		    $('.mail_select_user .mail_select_all').show();
		    
		}
		$('.mail_select_user .search_results').html(res.ui).show();

				
	    }, 'json');
	    return false;
	});
	
	$('.mail_select_user .search_results').on('click','.btn_add', function(){
	    
	    $('.'+$('.mail_select_user input[name="target"]').val()+' .mail_to_list').append($(this).closest('.mail_user'));
	    resetAutoSave();
	    return false;
	});
	
	$('.mail_select_user .mail_select_all').click(function(){
	    
	    $('.mail_select_user .search_results .btn_add').click();
	    resetAutoSave();
	    return false;
	});
	
	$('.mail_select_user .formsubbutton').click(function(){
	    
	    $('.mail_select_user').css({'margin-left': 0});
	    $('.mail_to_list').css({'border-color': 'black'});
	    return false;
	});
	
	$('.mail_to_list').on('click', '.mail_user .btn_remove', function(){
	    
	    $('.mail_select_user .search_results').append($(this).closest('.mail_user'));
	    resetAutoSave();
	    return false;
	    
	});
	
	$('.mail_to_btn .btn_select_ppl').click(function(){
	    
	    var target = $(this).find('.target').html()
	    
	    $('.mail_select_user').css({'margin-left': -380});
	    $('.mail_select_user input[name="target"]').val(target);
	    $('.mail_select_user .search_results').empty().hide();
	    $('.mail_select_user .mail_select_all').hide();
	    $('.'+target+' .mail_to_list').css({'border-color': 'orange'});
	    
	    return false; 
	    
	});
	
	
    }
}

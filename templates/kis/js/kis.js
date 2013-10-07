kis = {
    
    paths	: [],
    params	: {},
    child	: 0,
    children	: [],
    user_type	: '',
    available_apps: {},
    title	: KIS_TITLE,
    lang	: 'en',
    lock_message: false,
    uploaders	: [],
    route: function(paths, params){
   
	if (this.lock_message!==false){
	    if (confirm(this.lock_message)){
		this.unlock();
	    }else{
		return false;
	    }
	}
	this.paths 	= paths;
	this.params 	= params;
	
	this.loadBoard();
	return true;

    },
    loadBoard: function(){

	var that = this;
	$.each(this.uploaders, function(i, uploader){
	    uploader.destroy();//clean up uploaders
	});
	this.uploaders = [];
	
	switch (this.paths[0]){
	    	
	    case 'logs':	
		return this.loadLogs();
	    case 'myaccount':
		return this.loadMyAccount();
	    case 'apps':
		return this.loadApps();
	    default:
		return this.loadPortal();

	}
		
    },
    loadApps: function(){
	
	var app = this.paths[1];

	if (typeof(this.available_apps[app])=='undefined') return this;
	
	this.current_app = this.available_apps[app];
	var title = this.current_app['title'];
	var background = this.current_app['background'];
	
	this.title = KIS_TITLE+' > '+title;
	this.setNavigationItems([]);
			    		
	$('#module_page').removeClass().addClass('board '+background);				
	$('#module_page .board_main_content').append($('.board_loading_overlay').clone().show());
	$('#module_page .module_title').html(title).attr('href','#/apps/'+kis.paths[1]+'/');
	$('#module_page .navigation_bar .navigation_item').remove();
	$('.parent_btn .btn_menu').removeClass('btn_menu_on');
	$('.parent_btn .btn_logs').removeClass('btn_logs_on');
	
	if (typeof(this.available_apps[app].admin) != 'undefined'){
	    if (this.available_apps[app].admin != ''){
		$('#module_page .module_admin').show().find('a').attr('href', this.available_apps[app].admin).attr('target', app+'_admin');
	    }else{
		$('#module_page .module_admin').show().find('a').attr('href', '#/apps/'+kis.paths[1]+'/admin/').removeAttr('target');
	    }
	}else{
	    $('#module_page .module_admin').removeAttr('href').removeAttr('target').hide();
	}
	
	var q = this.paths.slice(2).join('/');
	var params = decodeURIComponent($.param(this.params));
	var app_url = 'apps/'+app+'/?q='+q;
	var app_callback = function(content){
	    $('#module_page .board_main_content').html(content);
	};
	
	if (typeof(kis[app]) == 'undefined'){
	    
	    $.ajax({
		url: KIS_JS_ROOT+'apps/kis.'+app+'.js',
		cache: false,
		dataType: "script",
		complete: function(){
		    $.get(app_url, params, app_callback);
		}
	    });
	    
	}else{
	    $.get(app_url, params, app_callback);
	}
	
	return this.showBoard(2);
    },
    loadMyAccount: function(){
	
	var title = $('.btn_user_acc.'+this.lang).attr('title');
	this.title = KIS_TITLE+' > '+title;
	this.setNavigationItems([]);
			    		
	$('#module_page').addClass('wood');				
	$('#module_page .board_main_content').append($('.board_loading_overlay').clone().show());
	$('#module_page .module_title').html(title).attr('href','#/myaccount/');
	$('#module_page .navigation_bar .navigation_item').remove();
	$('#module_page .module_admin').hide();
	$('.parent_btn .btn_menu').removeClass('btn_menu_on');
	$('.parent_btn .btn_logs').removeClass('btn_logs_on');
		
	var q = this.paths.slice(1).join('/');
	var params = decodeURIComponent($.param(this.params));

	$.get('myaccount/?q='+q, params, function(content){
	    $('#module_page .board_main_content').html(content);
	});
	
	return this.showBoard(2);
	
    },
    loadLogs: function(){
		
	if (this.user_type!=KIS_USERTYPE_PARENT) return this;

	this.title = KIS_TITLE+' > '+'Logs';
	this.setNavigationItems([]);

	$('.parent_btn .btn_logs').addClass('btn_logs_on');
	$('.parent_btn .btn_menu').removeClass('btn_menu_on');
	$('#module_page .board_main_content').empty();
		
	var q = this.paths.slice(1).join('/');
	var params = decodeURIComponent($.param(this.params));
	
	$.get('logs'+'/?q='+q, params, function(content){
	    $('#update_page .board_main_content').html(content);
	});
	
	return kis.showBoard(0);
	
    },
    loadPortal: function(){
	
	this.loadNotifications();
	
	this.title = KIS_TITLE;
	this.setNavigationItems([]);
	
	$('.parent_btn .btn_logs').removeClass('btn_logs_on');
	$('.parent_btn .btn_menu').addClass('btn_menu_on');
	$('#module_page .board_main_content').empty();
	
	return this.showBoard(1);
    },
    showBoard: function(i){
	
	$('.container_slider').css({'margin-left': -100*i+'%'}).find('> .board').eq(i).css({opacity: 1}).siblings().css({opacity: 0});
	
	return this;
    },
    loadNotifications: function(){
	var that = this;
	
	$.get('ajax.php?action=getnotifications',function(notifications){
	    
	    if (notifications.error){
		that.stop(2);
	    }
	    
	    var total = 0;
	    
	    $('.module_list li a em').hide();
	    for (var app in notifications){
		
		var count 	= parseInt(notifications[app]);
		var target 	= $('.module_list .app_'+app+' em').html(count);
		
		if (count > 0) target.show();
		total += target.length>0? count: 0;
		
	    }
	   
	    total > 0? $('.btn_menu em').show(): $('.btn_menu em').hide();
	    
	}, 'json');
    
	return this;
    },
    setFilterParams: function(params){
	
	
	var filter_params = $('.filter_form').map(function(){
	    return $(this).serialize();
	}).get().join('&');
	
	if (params){
	    filter_params += '&'+params;
	}
	
	$.address.queryString((filter_params));
	
	return this;
    	
    },
    setNavigationItems: function(items){
	
	$('#module_page .board_top_content .navigation_bar .navigation_item').remove();
	var title = this.title;
	
	$.each(items, function(i, item){
	
	    $('#module_page .board_top_content .navigation_bar').append($('<span>').addClass("navigation_item").text(item));
	    title += ' > '+item;

	});
	$.address.title(title);
	
	return this;
    },
    sort: function(sortby, order){
	
	return this.setFilterParams({sortby: sortby, order: order});
	
    },
    changeChild: function(child_id){
	
	var that = this;
	
	if (child_id=='') return this;
	$('.parent_btn').fadeOut();
	if (this.user_type != KIS_USERTYPE_PARENT) return this;
		
	$.post('ajax.php?action=setcurrentchild', {child: child_id}, function(child){
	    
	    $('.student_name em').attr('class', child.gender == 'M'? 'boy': 'girl');
	    $('.student_name span, .update_info h1').html(child['user_name_'+that.lang]);
	    $('.parent_btn').fadeIn();
	    $('.update_info .student_class').html(child.class_name+' - '+child.class_number);
	    $('.update_info .student_class_teacher').html($.map(child.class_teachers, function(class_teacher){
		    return class_teacher['user_name_'+that.lang];
		}).join(',')
	    );
	    $('.update_photo img').attr('src', child.photo);
	    child_id = child.user_id;
	    
	    if (that.child != child_id){
		
		that.loadBoard();
		that.child = child_id;
	    }
	    
	}, 'json');
	
	return this;
    },
    uploader: function(params){
	
	params = $.extend({}, KIS_UPLOADER_DEFAULT_PARAMS, params);  
	var uploader = new plupload.Uploader(params);
	
	$('#'+params.drop_element).append($('.attach_file_overlay').clone())
	
	uploader.bind('Init', function(up, init_params) {
	    
	    if (init_params.runtime=='html5'){
		
		var hide_interval; 
		$('#'+params.drop_element).on('dragover dragenter',function(e){
		    $(this).find('.attach_file_overlay').show();
		    clearTimeout(hide_interval);
		    return false;
		}).on('dragleave dragend drop',function(e){
		    hide_interval = setTimeout(function(){
			$('#'+params.drop_element+' .attach_file_overlay').hide();
		    },500);
		    
		    return false;
		});
	    }else{
		$('#'+params.drop_element+' .attach_file_overlay').hide();
	    }
	});
	
	uploader.init();
	
	if (params.start_button != null){
	    $('#'+params.startButton).click(function(){
		uploader.start();
	    });
	}
	
	uploader.bind('FilesAdded', function(up, files) {
	    $('#'+params.drop_element+' .attach_file_overlay').hide();
	});
	if (params.auto_start){
	    uploader.bind('FilesAdded', function(up, files) {
		up.refresh(); 
		this.start();
	    });
	}
	uploader.bind('FilesAdded', params.onFilesAdded);
	uploader.bind('UploadProgress', params.onUploadProgress);
	uploader.bind('FileUploaded', params.onFileUploaded);
	uploader.bind('Error', params.onError);
	
	this.uploaders.push(uploader);
	return uploader;
    },
    datepicker: function(target){
	
	return $(target).prop('readonly', true).datepicker({
	    showOn: "button",
	    buttonImage: KIS_DATEPICKER_IMAGE_URL,
	    buttonImageOnly: true,
	    buttonText: '',
	    default_date: 0,
	    changeYear: true,
	    changeMonth: true,
	    gotoCurrent: true,
	    dateFormat: 'yy-mm-dd'
	});
	    
    },
    editor: function(target){
	    
	$('#'.target).hide();
	var fck = new FCKeditor(target);
	fck.BasePath = KIS_EDITOR_URL;
	fck.Height =   "360px";
	fck.Config.DefaultLanguage = this.lang=='b5'?'zh':'en';
	fck.Config.SkinPath = KIS_EDITOR_URL+"editor/skins/office2003/";
	fck.ToolbarSet = "Basic2";
	fck.ReplaceTextarea();
			
	fck.finish = function(){
	    
	    if (typeof(FCKeditorAPI) != 'undefined'){
		var instance = FCKeditorAPI.GetInstance(target);
		if (typeof(instance) != 'undefined'){
		    FCKeditorAPI.GetInstance(target).UpdateLinkedField();
		}
	    }
	    
	    return this;
	};
	
	fck.keypress = function(callback){

	    try{
		$(FCKeditorAPI.GetInstance(target).EditorDocument).on('keypress', callback);	
	    }catch(e){
		FCKeditor_Callbacks[target] = function(){
		    $(FCKeditorAPI.GetInstance(target).EditorDocument).on('keypress', callback);	
		}
	    }
	    
	    return this;
	}
	
	fck.html = function(content){
	    
	    if (typeof(FCKeditorAPI) != 'undefined'){
		var instance = FCKeditorAPI.GetInstance(target);
		if (typeof(instance) != 'undefined'){
		    FCKeditorAPI.GetInstance(target).SetHTML(content);
		}
	    }
	    
	    return this;
	}
	return fck;
	
    },
    init: function(){
	var that = this;
		
	$.get('ajax.php?a=startup',function(res){that.start(res);},'json');
	  	    	    
	$(document).on('change', '.filter_form select.auto_submit', function(){
	    
	    that.setFilterParams();
	    
	}).on('submit', '.filter_form', function(){
	    
	    that.setFilterParams();
	    return false;
	    
	}).on('click', '.common_table_list .sortbutton', function(){
	    // sort table
	    
	    var form = $('#sort_form');
	    var sort = $(this).attr('href').replace('#','').split(',');
	    if (form.length){
		
		form.find('input[name="sortby"]').val(sort[0]);
		form.find('input[name="order"]').val(sort[1]);
		that.setFilterParams();
		
	    }else{
		that.setFilterParams('sortby='+sort[0]+'&order='+sort[1]);
	    }
	    
	    return false;
	
	}).on('change', '#table_filter select.year, #table_filter select.month', function(){
	    // year, month select
	    
	    $.address.path($(this).val());
	    
	}).on('click', '.checkbox_check_all', function(){
	
	    $('.checkbox_item').prop('checked', $(this).is(':checked'));
	    
	}).on('click', '.common_table_bottom .prev, .common_table_bottom .next', function(){
	    // page bar
	    var target_page = $(this).attr('href').replace('#','');
	    if (target_page != $('.common_table_bottom select.change_page').val()){
		$('.filter_form input[name="page"]').val(target_page);
		that.setFilterParams();
	    }
	    return false;
	
	}).on('change', 'select.change_page', function(){
	    
	    $('.filter_form input[name="page"]').val($(this).val());
	    that.setFilterParams();
	
	}).on('change', 'select.change_amount', function(){
	    
	    $('.filter_form input[name="amount"]').val($(this).val());
	    that.setFilterParams();
	
	}).on('submit', '#page_form', function(){
	    
	    that.setFilterParams();
	    return false;
	
	}).ajaxError(function(event, xhr, settings) {
	    
	    if (xhr.status == 403){ //session lost, force stop
		that.stop(xhr.responseText);
	    }
	});
	    
	
	$('#login_form').submit(function(){
	    
	    $('#login_form .loading_msg').show();
	    $('#login_form .error_msg').hide();
	    $('#login_button').prop('disabled', true);
	    
	    $.post('/login.php', $(this).serialize(), function(res){
		that.start(res);
	    }, 'json');
	    return false;
	    
	});
	
	$('.btn_lang_eng, .btn_lang_chn').click(function(){
	    
	    that.showLoading();
	    $.get($(this).attr('href'), {url: '/kis/ajax.php'}, function(res){
		that.start(res);
	    }, 'json');
	    
	    return false;
	    
	});
	
	$('.btn_login a').click(function(e){
	    
	    $('#login_button').click();
	    
	})
	
	$('.btn_logout').click(function(e){
	    var href = $(this).attr('href');

	    if (confirm($(this).find('.message').html())){
		
		$('#container').fadeOut(500);
		$('#logout_message').show();
		$.get('/logout.php?target_url=/kis/ajax.php?a=logout');
	    }

	    return false;
	});
	
	$('.student_text').click(function(){

	    $('.student_choose').toggle();
	    $('.student_name span').toggle();
	    
	});
    
	
	$('.student_choose').change(function(){
	    
	    that.changeChild($(this).val());
	    $(this).hide();
	    $('.student_name span').show();
	    
	});
	

	return this;
	
    },
    start: function(params){
	
	var that = this;	
	this.user_type = params.user.type;
	this.child = params.user.current_child;
	this.children = params.user.children;
	this.lang = params.lang;
	this.available_apps = params.available_apps;
	this.changeChild(this.child);
	
	this.reattach();
	
	var parameters = {};
	$.each($.address.parameterNames(), function(i, name){
	    parameters[name] = $.address.parameter(name);
	});
		
	var children_options = $.map(this.children, function(name, id){
	    return '<option value="'+id+'">'+name+'</option>';
	});
	
	var app_icons = $.map(params.available_apps, function(app, name){
		
	    var anchor = $('<a>').attr('class', app.image).append($('<span>').html(app.title)).append($('<em>').hide());
	    if (app.href){
		anchor.attr('href', app.href).attr('target', name);	
	    }else{
		anchor.attr('href', '#/apps/'+name+'/');
	    }
	    return $('<li>').append(anchor).addClass('app_'+name);
	});
    
	that.route($.address.pathNames(), parameters);

	that.hideLoading();
	$('.student_choose').html(children_options.join());
	$('.student_choose').val(that.child);
	
	$('#login_form').fadeOut(300, function(){
	    $('#container').fadeIn(500);
	});
	
	$('#welcome_message').hide();
	$('#top_header, body').attr('class', params.background);
	$('.school_name').html(params.school.name);
	$('#top_header .logo').css({'background-image': 'url("'+params.school.logo+'")'});
	$('.user_type').html(params.user.type_title);
	$('.user_name').html(params.user.name);
	$('#main_portal .module_list').html(app_icons).sortable({
	    distance: 30,
	    delay: 300,
	    scroll: false,
	    containment: "#main_portal"
	});
	
	if (params.lang=='b5'){
	
	    $('.en').hide();
	    $('.b5').show();
	    $.datepicker.setDefaults($.datepicker.regional["zh-HK"]);
	}else{
	
	    $('.en').show();
	    $('.b5').hide();
	    $.datepicker.setDefaults($.datepicker.regional[""]);
	}
	    
	
	return this;
    },
    stop: function(stop_message){
	
	var that = this;
	
	$.address.unbind('change');
	$.address.value('');
	
	this.children = {};
	this.child = '';
	this.user_type = '';
	this.available_apps = [];
	this.title = KIS_TITLE;
	this.setNavigationItems([]);
		    
	$('#welcome_message').hide();
	$('#logout_message').hide();
	$('#container').fadeOut(500);
	$('#login_button').prop('disabled', false);
	$('body, #top_header').removeClass();
	$('.student_choose').empty();
	$('.school_name, .user_type, .user_name, #main_portal .module_list').empty();
	$('#login_form').fadeIn();
	$('#login_form .loading_msg').hide();
	$('#'+stop_message).fadeIn();
	    
	return this;

    },
    lock: function(message){
	
	this.lock_message = message;
	$(window).off('beforeunload').on('beforeunload', function(){return message;});
	return this;
    
    },
    unlock: function(){
	
	this.lock_message = false;
	$(window).off('beforeunload');
	return this;
    
    },
    reattach: function(callback){
	
	var that = this;
	$.address.unbind('change');
	
	if (typeof(callback) == 'function'){
	    callback();
	}
	
	$.address.bind('change', function(e){
	    return that.route(e.pathNames, e.parameters);
	});
	return this;
    },
    showLoading: function(){
	$.fancybox.showLoading();
	return this;
    },
    hideLoading: function(){
	$.fancybox.hideLoading();
	return this;
    },
    hasEmptyInputElements:function(jq_object){
	return jq_object.map(function(){return $.trim($(this).val())==''?true:null;}).length>0;
    }

};

FCKeditor_Callbacks = {};
FCKeditor_OnComplete = function(editorInstance){

    if (typeof(FCKeditor_Callbacks[editorInstance.Name]) == 'function'){
	FCKeditor_Callbacks[editorInstance.Name]();
    }
}
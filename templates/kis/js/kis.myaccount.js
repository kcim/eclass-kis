kis.myaccount = {
    
    contactinfo_init: function(lang){  
	
	$('.formbutton').click(function(){
	    
	    var email = $('input[name="email"]').css({'background-color': ''}).val();
	    $('#invalid_email').hide();
	  	    
	    if (!/^[\w_\.\-]+\@([\w_\.\-]+\.)+[\w]+$/.test(email)){
		$('#invalid_email').show();
		$('input[name="email"]').css({'background-color': '#FFAAAA'});
		return false;
	    }
	    kis.showLoading();
	    $.post('myaccount/ajax.php?action=updatedetail', $('.main_content_detail form').serialize(), function(){
		kis.hideLoading();
		
		alert(lang.recordsupdated);
		
	    });
	    return false;
	});
	
    },
    personalinfo_init: function(lang){
	
	if ($('#myacc_photo').length>0){
	    kis.uploader({
		browse_button:  'myacc_photo',
		url: 'myaccount/ajax.php?action=updatephoto',
		multi_selection: false,
		auto_start: true,
		onFilesAdded: function(up, files) {
		    $('#myacc_photo img').css({opacity: 0.5});
		},
		onUploadProgress: function(up, file) {
		    
		},
		onFileUploaded: function(up, file, info) {
		    
		    var res = $.parseJSON(info.response);
		    
		    if (res.error){
			alert(res.error);
		    }else if (res.personal_photo){
			$('#myacc_photo img').attr('src',res.personal_photo).show();
			$('#myacc_photo img').css({opacity: 1});
			$('#myacc_photo .mail_icon_form').show();
		    }
		    
		}
	    });
	}
	
	$('.formbutton').click(function(){
	    
	    $('.formbutton').prop('disabled', true);
	    
	    kis.showLoading();
	    $.post('myaccount/ajax.php?action=updatedetail', $('.main_content_detail form').serialize(), function(){
		kis.hideLoading();
		alert(lang.recordsupdated);
		$('.formbutton').prop('disabled', false);
		
	    });
	    return false;
	});
	
	$('#myacc_photo .btn_remove').click(function(){
	    
	    $(this).parent().hide();
	    $('#myacc_photo img').removeAttr('src').hide();
	    $.post('myaccount/ajax.php?action=removephoto');
	    
	    return false;
	})
	
    },
    changepassword_init: function(lang){
	
	$('.main_content_detail .formbutton').click(function(e){
	
	    $('.tabletextrequire').hide();
	    $('input[type="password"]').css({'background-color': ''});
	    
	    var password = $('input[name="password"]').val();
	    
	    if (password.length<6){
		
		$('#password_too_short').show();
		$('input[name="password"]').css({'background-color': '#FFAAAA'});
		
	    }else if (password!=$('input[name="password2"]').val()){
		
		$('#password_not_match').show();
		$('input[name="password2"]').css({'background-color': '#FFAAAA'});
		
	    }else{
		kis.showLoading();
		$.post('myaccount/ajax.php?action=updatepassword', $('#change_pw_form').serialize(), function(res){
		    kis.hideLoading();
		    if (res.error){
			alert(res.error);
			$('#incorrect_password').show();
			$('input[name="old_password"]').css({'background-color': '#FFAAAA'});
		    }else{
			alert(lang.passwordchanged);
		    }
		    
		}, 'json');
	    }
	    
	    return false;
	
	});
	
	
	$('input[name="password"]').keyup(function(){
	    
		var password = $(this).val();
		var show = '#pw_low';
		var security = 0;
		
		if (password.length >= 6 ){
		    
		    security += /[a-z]/.test(password)? 1: 0;
		    security += /[A-Z]/.test(password)? 1: 0;
		    security += /[0-9]/.test(password)? 1: 0;
		    security += /[^A-Za-z0-9]/.test(password)? 1: 0;
		    security += Math.floor((password.length-6)/2);  
		    
		}
		if (security>4){ show = '#pw_high'; }else if (security>2){ show = '#pw_mid'; }
		
		if (password.length>=0){
		    $('.text_remark').show().find(show).show().siblings('em').hide();
		}else{
		    $('.text_remark').hide();
		}
	});
    }
    
}
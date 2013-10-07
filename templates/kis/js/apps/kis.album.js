kis.album = {
    
    albums_init: function(){
	$('#my_album_only').click(function(){
	    $(this).closest('form').submit();
	    
	    
	})
	$('.album_list').masonry();
    },
    album_form_init: function(lang, options){
	
	var uploading_files_count = 0;
	var saveAlbum = function(callback){
	    $.post('apps/album/ajax.php?action=setalbum', $('.album_form').serialize(),function(res){
		kis.unlock();
		$('.album_form input[name="album_id"]').val(res.album_id);
		$('.album_form_remove').fadeIn();
		kis.setNavigationItems([res.album_title]);
		if (typeof(callback)=='function') callback();
	    },'json');
	}
	
	var savePhotoOrder = function(){
	    var photo_ids = $('.edit_photo_list .photo_ids').map(function(){return $(this).val()}).get();
	    var album_id = $('.album_form input[name="album_id"]').val();
	    $.post('./apps/album/ajax.php?action=reorderphotos', {photo_ids: photo_ids, album_id: album_id});
	}
	
	kis.uploader({
	    browse_button:  'add_photos',
	    url: './apps/album/ajax.php?action=addphoto',
	    resize : {width : 1920, height : 1920, quality : 100},
	    auto_start: false,
	    onFilesAdded: function(up, files) {
		
		var uploader = this;
		uploading_files_count+=files.length;
		
		$.each(files, function(i, file){
		
		    var item = $('#edit_photo_list_template li').clone();
		    item.attr('id', file.id).find('span').attr('title', file.name).append($('<div class="photo_progress">').progressbar());
		    
		    $('.edit_photo_list ul').append(item.fadeIn());
		   
		});
		
		saveAlbum(function(){
		    
		    kis.lock(lang.areyousureto+lang.stopuploadingphotos+'?');
		    //pick up new mail_id
		    uploader.settings.multipart_params= {album_id: $('.album_form input[name="album_id"]').val()};
		    uploader.start();
		});
	    },
	    onUploadProgress: function(up, file) {
		
		$('#'+file.id).find('.photo_progress').progressbar('value', file.percent);
	    },
	    onFileUploaded: function(up, file, info) {
		
		var res = $.parseJSON(info.response);
		
		if (res.error){
		    
		    $('#'+file.id+' .photo_progress').remove();
		    $('#'+file.id+' span').html('<h5>'+file.name+'</h5> '+res.error);
		    $('#'+file.id).fadeOut(2000);
		      
		}else if (res.thumbnail){
		    
		    $('#'+file.id+' .photo_progress').remove();
		    $('#'+file.id+' span').css({'background-image': 'url('+res.thumbnail+')'});
		    $('#'+file.id+' .input_desc').prop('disabled', false);
		    $('#'+file.id+' .photo_ids').val(res.photo_id);
		    $('#'+file.id+' .title').val(res.title);
		    $('#'+file.id+' .date_taken').val(res.date_taken);
		    $('#'+file.id+' .date_uploaded').val(res.date_uploaded);
		    $('#'+file.id+' .table_row_tool').show();
		    $('#'+file.id).addClass('uploaded');
		    
		    $('.filter_ordering').fadeIn();
		}
		
		if (--uploading_files_count == 0){
		    kis.unlock();
		}
	    }
	});
	
	kis.datepicker('.album_form_date_access input.date');
	
	$('.edit_photo_list ul').sortable({
	    containment: "#module_page",
	    zIndex: '7010',
	    update: savePhotoOrder,
	    opacity: 0.8,
	    items: '>li.uploaded'
	});
	
	var title = $('.album_title').text();;
	if (title != ''){
	    kis.setNavigationItems([title]);
	}

	if (options.photo_count==0){
	    $('.filter_ordering').hide();
	    $('.edit_photo_list .attach_file_overlay').show();
	    
	}
	
	$('.album_form :input').change(saveAlbum);
    
	$('.edit_photo_list').on('change','.input_desc', function(){
	
	    var photo_id = $(this).siblings('.photo_ids').val();
	    var album_id = $('.album_form input[name="album_id"]').val();
	    $.post('apps/album/ajax.php?action=updatephotodescription', {description: $(this).val(), photo_id: photo_id, album_id: album_id},function(){
		kis.unlock();
		
	    });
    
	});
		
	$('.edit_bottom .formbutton').click(function(){
	    
	    var album_id = $('.album_form input[name="album_id"]').val();
	    $.address.value(album_id? '/apps/album/'+album_id+'/': '/apps/album/');
 
	});
	
	$('.filter_ordering a').click(function(){
	    
	    var sort_field = $(this).attr('href').replace('#','');
	    var to_int = $(this).hasClass('int')
	    var order;
	    
	    if ($(this).hasClass('order_asc')){
		order = -1;
		$(this).removeClass('order_asc').addClass('order_dec');
	    }else{
		order = 1;
		$(this).removeClass('order_dec').addClass('order_asc');
	    }
	    
	    $(this).siblings().removeClass('order_dec order_asc');
	   
	    
	    var items = $('.edit_photo_list li.uploaded').sort(function(a, b){
		
		a = $(a).find('.'+sort_field).val().toLowerCase();
		b = $(b).find('.'+sort_field).val().toLowerCase();
		
		if (to_int){
		    a = parseInt(a);
		    b = parseInt(b);
		}
		
		if(a > b){ return 1*order; }else if(a < b){ return -1*order; }else{ return 0;}

	    });
	    
	    $('.edit_photo_list ul').html(items);
	    savePhotoOrder();
	    
	    return false;
	    
	});
	
	$('.edit_photo_list').on('click', 'li.uploaded .copy_dim', function(){
	    
	    if (confirm(lang.areyousureto+$(this).attr('title')+'?')){
		
		var photo_id = $(this).parent().siblings('.photo_ids').val();
		var album_id = $('.album_form input[name="album_id"]').val();
		
		$.post('apps/album/ajax.php?action=updatecoverphoto', {photo_id: photo_id, album_id: album_id});
	
	    }
  
	    return false;
	});
	
	$('.edit_photo_list').on('click', 'li.uploaded .delete_dim', function(){
	    
	    if (confirm(lang.areyousureto+$(this).attr('title')+'?')){
	    
		var photo_id = $(this).parent().siblings('.photo_ids').val();
		var album_id = $('.album_form input[name="album_id"]').val();
		
		$(this).closest('li').fadeOut(function(){$(this).remove();});
		$.post('apps/album/ajax.php?action=removephoto', {photo_id: photo_id, album_id: album_id});
		
	    }
	    
	    return false;
	});
	
	$('.album_form_remove').click(function(){
	   
	    if (confirm(lang.allphotoswillberemoved+" \n"+lang.areyousureto+$(this).text()+'?')){
		
		var album_id = $('.album_form input[name="album_id"]').val();
		
		kis.unlock();
		kis.showLoading();
		$.post('apps/album/ajax.php?action=removealbum', {album_id: album_id}, function(){
		    
		    kis.hideLoading();
		    $.address.value('/apps/album/');
		    
		});
		
	    }
	    
	    return false;
	    
	});
	
	$('.btn_select_ppl').click(function(){
	    
	    $('.album_form_date_access_select').fadeToggle();
	
	    return false;
	});
	
	$('#share_to_all').click(function(){

	    $('.select_date').fadeIn();
	    $('.select_groups').hide().prop('disabled', true);
	});
	
	$('#share_to_myself').click(function(){
	    
	    $('.select_date').hide();
	    $('.select_groups').hide().prop('disabled', true);
	});
	
	$('#share_to_groups').click(function(){
	    
	    $('.select_date').fadeIn();
	    $('.select_groups').fadeIn().prop('disabled', false);
	});
	
	
    },
    album_init:function(){
	   
	var title = $('.album_title').text();
	
	kis.setNavigationItems([title]);
	$('.photo_thumb_list ul').masonry();

	
    }
    
}
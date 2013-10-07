kis.iportfolio = {
    
    init: function(){
	$('ul.main_menu li#li_sbs').click(function(){
		window.open('/home/portfolio/teacher/management/sbs/index.php');
		return false;
	});	
	$('ul.main_menu li#li_learningportfolio').click(function(){
		window.open('/home/portfolio/learning_portfolio_v2/');
		return false;
	});
    },
    
    start_init: function(){
	
	$('#module_page .board_main_content').append($('.board_loading_overlay').clone().show());
	
	$('.login_iportfolio_page').load(function(){
	    $('#module_page .board_main_content').empty();
	    kis.loadBoard();
	});
    
    },
    
    information_init: function(){
    },
	reloadSemester: function(isOnChange){
		var schoolYearId = $('select#school_year_id').val();
		$('#span_term').load(
			'apps/iportfolio/ajax.php?action=getTermSelection', 
			{
				AcademicYearID: schoolYearId,
				isOnChange: isOnChange
			}
		);		
	},			
    learningportfolio_init: function(){
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/zh_HK/all.js#xfbml=1&status=0";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));	
		var app_callback = function(content){
			$('#module_page .board_main_content').html(content);
			if(typeof FB){
				FB.XFBML.parse($('#lp_list').get(0));
			}
		};	
		var reloadPortfolio = function(){
			var params = decodeURIComponent($.param(kis.params));
			var app_url = 'apps/iportfolio/?q=learningportfolio';
			$.get(app_url, params, app_callback);				
		}		
		$(".fancybox_iframe").fancybox({
			maxWidth: 600,
			nextEffect: 'fade',
			prevEffect: 'fade',
			type: 'iframe',
			beforeClose: function(){
				reloadPortfolio();
			}
		});
		$(".fancybox").fancybox({
			nextEffect: 'fade',
			prevEffect: 'fade',
			margin: 0,
			padding: 0,
			type: 'ajax',
			beforeClose: function(){
			   reloadPortfolio();
			}
		});
		
    },	
    teacher_award_edit_init: function(lang){
		kis.datepicker('#award_date');
		var studentId = $('#studentId').val();
		var classId = $('#classId').val();
		var return_path = '/apps/iportfolio/schoolrecords/awards/';
		return_path += (typeof studentId!='undefined')?'student/?studentId='+studentId:'?classId='+classId;
		$('.mail_to_btn .btn_select_ppl').click(function(){
			$('.mail_select_user').css({'margin-left': -380});
			$('.mail_select_user .search_results').empty().hide();
			$('.mail_select_user .mail_select_all').hide();
			
			return false; 
			
		});	
		$('div.edit_bottom input#cancelBtn').click(function(){
				$.address.value(return_path);
			return false;
		});			
			
		$('select#school_year_id').change(function(){
			kis.iportfolio.reloadSemester();
		});			
		$('.mail_select_user').submit(function(){
			
			var form = this;
			var exclude_list = $('.mail_to_list .mail_user input').map(function(){
			return $(this).val()
			}).get().join(',');
			$('.mail_select_user input[name="exclude_list"]').val(exclude_list);
			
			kis.showLoading();
			$.post('apps/iportfolio/ajax.php?action=searchusers', $(this).serialize(), function(res){
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
		var checkAwardForm = function(action){
			var validated = false;
			if(action=='new'){
				$("input[name='target_user[]']").each(function() {
					if ($(this).val() != '') {
						validated = true;
						return false;
					} 
				});
				if(!validated){
					alert(lang.please_select_student);
					return false;
				}	
			}
		 	if(kis.hasEmptyInputElements($('#award_name'))){
				alert(lang.please_fill_in+lang.award_title);
				$('#award_name').focus();
				return false;
			}
		 	if(kis.hasEmptyInputElements($('#award_date'))){
				alert(lang.please_fill_in+lang.award_date);
				$('#award_date').focus();
				return false;
			}			
			return true;
		}			
		$('form#award_form').submit(function(){
			var action = $('#school_record_action').val();
			if(checkAwardForm(action)){
				kis.showLoading();
				$.post('apps/iportfolio/ajax.php?action=saveAwardRecord', $(this).serialize(), function(){
					kis.hideLoading();
					$.address.value(return_path);
				});	
			}
			return false;
		});		
		$('.mail_select_user .search_results').on('click','.btn_add', function(){
			$('.mail_to_list').append($(this).closest('.mail_user'));
			return false;
		});
		
		$('.mail_select_user .mail_select_all').click(function(){
			
			$('.mail_select_user .search_results .btn_add').click();
			return false;
		});
		
		$('.mail_select_user .formsubbutton').click(function(){
			
			$('.mail_select_user').css({'margin-left': 0});
			return false;
		});
		
		$('.mail_to_list').on('click', '.mail_user .btn_remove', function(){
			
			$('.mail_select_user .search_results').append($(this).closest('.mail_user'));
			return false;
			
		});		
    },
	teacher_activity_edit_init: function(lang){
		var studentId = $('#studentId').val();
		var classId = $('#classId').val();
		var return_path = '/apps/iportfolio/schoolrecords/activities/';
		return_path += (typeof studentId!='undefined')?'student/?studentId='+studentId:'?classId='+classId;
		$('.mail_to_btn .btn_select_ppl').click(function(){
			$('.mail_select_user').css({'margin-left': -380});
			$('.mail_select_user .search_results').empty().hide();
			$('.mail_select_user .mail_select_all').hide();
			
			return false; 
			
		});	
		$('div.edit_bottom input#cancelBtn').click(function(){
			$.address.value(return_path);
			return false;
		});			
		$('select#school_year_id').change(function(){
			kis.iportfolio.reloadSemester(0);
		});			
		$('.mail_select_user').submit(function(){
			
			var form = this;
			var exclude_list = $('.mail_to_list .mail_user input').map(function(){
			return $(this).val()
			}).get().join(',');
			$('.mail_select_user input[name="exclude_list"]').val(exclude_list);
			
			kis.showLoading();
			$.post('apps/iportfolio/ajax.php?action=searchusers', $(this).serialize(), function(res){
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
		var checkActivityForm = function(action){
			var validated = false;
			if(action=='new'){
				$("input[name='target_user[]']").each(function() {
					if ($(this).val() != '') {
						validated = true;
						return false;
					} 
				});
				if(!validated){
					alert(lang.please_select_student);
					return false;
				}	
			}
		 	if(kis.hasEmptyInputElements($('#activity_name'))){
				alert(lang.please_fill_in+lang.activity_name);
				$('#activity_name').focus();
				return false;
			}
		 	if(kis.hasEmptyInputElements($('#role'))){
				alert(lang.please_fill_in+lang.role);
				$('#role').focus();
				return false;
			}			
			return true;
		}			
		$('form#activity_form').submit(function(){
			var action = $('#school_record_action').val();
			if(checkActivityForm(action)){
				kis.showLoading();
				$.post('apps/iportfolio/ajax.php?action=saveActivityRecord', $(this).serialize(), function(){
					kis.hideLoading();
					$.address.value(return_path);
				});	
			}
			return false;
		});		
		$('.mail_select_user .search_results').on('click','.btn_add', function(){
			$('.mail_to_list').append($(this).closest('.mail_user'));
			return false;
		});
		
		$('.mail_select_user .mail_select_all').click(function(){
			
			$('.mail_select_user .search_results .btn_add').click();
			return false;
		});
		
		$('.mail_select_user .formsubbutton').click(function(){
			
			$('.mail_select_user').css({'margin-left': 0});
			return false;
		});
		
		$('.mail_to_list').on('click', '.mail_user .btn_remove', function(){
			
			$('.mail_select_user .search_results').append($(this).closest('.mail_user'));
			return false;
			
		});		
    },	
	switch_student_list: function(action){
		var cur_index = $('#studentId')[0].selectedIndex;
		var new_index = (action=='next')?cur_index+1:cur_index-1;
		var max_index = $('#studentId')[0].length;
		if(new_index>=max_index || new_index<0){
			return false;
		}else{ 
			$('#studentId')[0].selectedIndex = new_index;
			return true;
		}
	},	
    teacher_schoolrecord_list_init: function(lang){
		$('div.Content_tool a.new').click(function(){
			var retrieve_type = $('#retrieve_type').val();
			var classId = $('#classId').val();
			var studentId = $('#studentId').val();
			var new_path = '/apps/iportfolio/schoolrecords/'+retrieve_type+'/new/?classId='+classId;
			new_path += (typeof studentId!='undefined')?'&studentId='+studentId:'';
			$.address.value(new_path);
			return false;
		});		
		$('div.table_row_tool a.edit_dim').click(function(){
			var retrieve_type = $('#retrieve_type').val();
			var recordId = $(this).closest('tr').attr('id').split('_')[1];
			$.address.value('/apps/iportfolio/schoolrecords/'+retrieve_type+'/edit/?recordId='+recordId);
			return false;
		});	
		$('div.table_row_tool a.copy_dim').click(function(){
			kis.showLoading();
			var retrieve_type = $('#retrieve_type').val();
			var recordId = $(this).closest('tr').attr('id').split('_')[1];
				$.post('apps/iportfolio/ajax.php?action=copySchoolRecord', {recordId:recordId,retrieve_type:retrieve_type}, function(data){
					kis.hideLoading().loadBoard();
				});
			return false;
		});			
		$('div.table_row_tool a.delete_table_record').click(function(){
			var retrieve_type = $('#retrieve_type').val();
			var recordId = $(this).closest('tr').attr('id').split('_')[1];
			if (confirm(lang.are_you_sure_to_delete)){
				kis.showLoading();
				$.post('apps/iportfolio/ajax.php?action=removeSchoolRecord', {recordId:recordId,retrieve_type:retrieve_type}, function(data){
					kis.hideLoading().loadBoard();
				});
			} 
			return false;
		});	
				
		$('select#school_year_id').change(function(){
			$("#school_year_term_id").val(0);
			$("form.filter_form").submit();
		});		
 		$('div.student_no a').click(function(){
			var className = $(this).attr('class');
			if(kis.iportfolio.switch_student_list(className)){
				studentId = $('#studentId').val();
				$("form.filter_form").submit();
			}
			return false;
		});	 		
    },    
    teacher_class_iportfolio_init: function(){
		$('div.toggle_tool li a').click(function(){
			var classId = $('#classId').val();
			var view = this.id.split('_')[1];
			$('#view').val(view);
			$("form.filter_form").submit();
			return false;
		});
		$('.ipf_student_list ul').masonry();
    },    	
	teacher_student_info_init: function(lang){
		var classId,studentId,retrieve_type,showPage;			
		$('select#school_year_id').change(function(){
			kis.iportfolio.reloadSemester(0);
		});	
	
		$('div.module_tab a').click(function(){
			classId = $('#classId').val();
			studentId = $('#studentId').val();
			retrieve_type =this.id.split('_')[1];
			showPage = $('#showPage').val();
			$.address.value('/apps/iportfolio/studentaccount/studentInfo/?classId='+classId+'&studentId='+studentId+'&showPage='+showPage+'&recordType=view_'+retrieve_type);
			return false;
		});	
		$('div.Content_tool a.new').click(function(){
			classId = $('#classId').val();
			studentId = $('#studentId').val();
			retrieve_type = $('#retrieve_type').val();
			showPage = $('#showPage').val();
			$.address.value('/apps/iportfolio/studentaccount/studentInfo/?classId='+classId+'&studentId='+studentId+'&showPage='+showPage+'&recordType=new_'+retrieve_type);
			return false;
		});			
		$('div.table_row_tool a.edit_dim').click(function(){
			var retrieve_type = $('#retrieve_type').val();
			var recordId = $(this).closest('tr').attr('id').split('_')[1];
			var classId = $('#classId').val();
			var studentId = $('#studentId').val();
			showPage = $('#showPage').val();
			$.address.value('/apps/iportfolio/studentaccount/studentInfo/?classId='+classId+'&studentId='+studentId+'&showPage='+showPage+'&recordType=edit_'+retrieve_type+'&recordId='+recordId);
			return false;
		});	
		$('div.table_row_tool a.copy_dim').click(function(){
			kis.showLoading();
			var retrieve_type = $('#retrieve_type').val();
			showPage = $('#showPage').val();
			var recordId = $(this).closest('tr').attr('id').split('_')[1];
				$.post('apps/iportfolio/ajax.php?action=copySchoolRecord', {recordId:recordId,retrieve_type:retrieve_type,showPage:showPage}, function(data){
					kis.hideLoading().loadBoard();
				});
			return false;
		});			
		$('div.table_row_tool a.delete_table_record').click(function(){
			var retrieve_type = $('#retrieve_type').val();
			var recordId = $(this).closest('tr').attr('id').split('_')[1];
			showPage = $('#showPage').val();
			 if (confirm(lang.are_you_sure_to_delete)){
				kis.showLoading();
				$.post('apps/iportfolio/ajax.php?action=removeSchoolRecord', {recordId:recordId,retrieve_type:retrieve_type,showPage:showPage}, function(data){
					kis.hideLoading().loadBoard();
				});
			} 
			return false;
		});			
		
		var getStudentInfo = function(action){
			kis.showLoading();
			studentId = $('#studentId').val();
			$.post('apps/iportfolio/ajax.php?action=getStudentInfo', {studentId:studentId,infoType:action}, function(data){
				$('.ipf_board').html(data);	
				if(action=='edit'){
					$('div.edit_bottom').show();
					$('div.common_table_tool a.tool_edit').hide();
					kis.datepicker('#DateOfBirth');
				}else{
					$('div.edit_bottom').hide();
					$('div.common_table_tool a.tool_edit').show();
				}
				kis.hideLoading();
			});  
		}	
		var checkStudentForm = function(){
			if(kis.hasEmptyInputElements($('#EnglishName'))){
				alert(lang.please_fill_in+lang.eng_name);
				$('#EnglishName').focus();
				return false;
			}
			$('div.ipf_info_g_detail').each(function(i){
				var hasInput = false, hasRelationship = false, hasEnglishName = false;
				$(this).find('input').each(function(){
					if(!kis.hasEmptyInputElements($(this))){
						hasInput = true;
						if(this.id == 'EnglishNameP'+(i+1)){
							hasEnglishName = true;
						}
					}
				});
				if(!kis.hasEmptyInputElements($('#RelationshipP'+(i+1)))){
					hasInput = true;
					hasRelationship = true;
				}
				if(hasInput){
					if(!hasEnglishName){
						alert(lang.please_fill_in+lang.eng_name);
						$('#EnglishNameP'+(i+1)).focus();
						return false;
					}
					if(!hasRelationship){
						alert(lang.please_fill_in+lang.relationship);
						$('#RelationshipP'+(i+1)).focus();
						return false;
					}					
				}
			});
			
			return true;
		}	
		var checkSchoolRecordForm = function(type){
			if(type=='awards'){
				if(kis.hasEmptyInputElements($('#award_name'))){
					alert(lang.please_fill_in+lang.award_title);
					$('#award_name').focus();
					return false;
				}
			}else{
				if(kis.hasEmptyInputElements($('#activity_name'))){
					alert(lang.please_fill_in+lang.activity_name);
					$('#activity_name').focus();
					return false;
				}			
				if(kis.hasEmptyInputElements($('#role'))){
					alert(lang.please_fill_in+lang.role);
					$('#role').focus();
					return false;
				}				
			}
				
			return true;
		}			
		$('div.common_table_tool a.tool_edit').click(function(){
			getStudentInfo('edit');
			return false;
		});
		$('div.student_no a').click(function(){
			var className = $(this).attr('class');
			if(kis.iportfolio.switch_student_list(className)){
				showPage = $('#showPage').val();
				studentId = $('#studentId').val();
				classId = $('#classId').val();
				$.address.value('/apps/iportfolio/studentaccount/studentinfo/?classId='+classId+'&studentId='+studentId+'&showPage='+showPage); 
			}
			return false;
		});			
		$('select#classId').change(function(){
			classId = $('#classId').val();
			showPage = $('#showPage').val();
			$.address.value('/apps/iportfolio/studentaccount/studentinfo/?classId='+classId+'&showPage='+showPage); 
			return false;
		});		
		$('div.teacher_iportfolio_student div.btn_ipf_tool a').click(function(){
			if(this.id!=''){
				var this_page = this.id.split('_')[1];
				classId = $('#classId').val();
				studentId = $('#studentId').val();
				$.address.value('/apps/iportfolio/studentaccount/studentinfo/?classId='+classId+'&studentId='+studentId+'&showPage='+this_page); 
				return false;
			}
		});	
		$('div.edit_bottom input#submitBtn').click(function(){
			showPage = $('#showPage').val();
			switch(showPage){
				case 'schoolrecord':
					retrieve_type = $('#retrieve_type').val();
					if(checkSchoolRecordForm(retrieve_type)){
						kis.showLoading();
						$.post('apps/iportfolio/ajax.php?action=saveSchoolRecord', $("form#recordForm").serialize(), function(data){	
							kis.hideLoading();	
							classId = $('#classId').val();
							studentId = $('#studentId').val();							
							$.address.value('/apps/iportfolio/studentaccount/studentInfo/?classId='+classId+'&studentId='+studentId+'&showPage=schoolrecord&recordType=view_'+retrieve_type);
						});
					}
					break;
				case 'assessment':
					retrieve_type = $('#retrieve_type').val();
					if(checkSchoolRecordForm(retrieve_type)){
						kis.showLoading();
						$.post('apps/iportfolio/ajax.php?action=saveSchoolRecord', $("form#recordForm").serialize(), function(data){	
							kis.hideLoading();	
							classId = $('#classId').val();
							studentId = $('#studentId').val();							
							$.address.value('/apps/iportfolio/studentaccount/studentInfo/?classId='+classId+'&studentId='+studentId+'&showPage=schoolrecord&recordType=view_'+retrieve_type);
						});
					}
					break;					
				default:					
					if(checkStudentForm()){
						kis.showLoading();
						$.post('apps/iportfolio/ajax.php?action=updateStudentInfo', $("form#studentInfoForm").serialize(), function(data){
							kis.hideLoading();	
							getStudentInfo('view');
						});
					}
			}
			
			return false;
		});			
		$('div.edit_bottom input#cancelBtn').click(function(){
			showPage = $('#showPage').val();
			switch(showPage){
				case 'schoolrecord':
					classId = $('#classId').val();
					studentId = $('#studentId').val();
					retrieve_type = $('#retrieve_type').val();
					$.address.value('/apps/iportfolio/studentaccount/studentInfo/?classId='+classId+'&studentId='+studentId+'&showPage=schoolrecord&recordType=view_'+retrieve_type);
					break;
				default:getStudentInfo('view');
			}
			return false;
		});	
		$('.assessment_upload_btn').each(function(i){
			studentId = $('#studentId').val();
			classId = $('#classId').val();
			var assessmentId = $(this).closest( "td" ).attr("id").split('_')[2];
			kis.uploader({
				browse_button:  this.id,
				url: './apps/iportfolio/ajax.php?action=addStudentAssessment&assessmentId='+assessmentId+'&classId='+classId+'&studentId='+studentId,
				auto_start: true,
				onFilesAdded: function(up, files) {
					var uploader = this;
					$.each(files, function(i, file){
						$('#td_attachment_'+assessmentId).append('<a class="temp_file_attachment">'+file.name+'</a>').append($('<em class="progress">'));
						$('#td_attachment_'+assessmentId+' input.assessment_upload_btn').hide();
					});
				},
				onUploadProgress: function(up, file) {
					$('#td_attachment_'+assessmentId+' .progress').html(''+file.percent+'%');
				},
				onFileUploaded: function(up, file, info) {
				 	var res = $.parseJSON(info.response);
					$('#td_attachment_'+assessmentId+' a.temp_file_attachment').remove();
					$('#td_attachment_'+assessmentId+' em.progress').remove();
					$('#td_attachment_'+assessmentId+' span.view_attachment').show();
					$('#td_attachment_'+assessmentId+' span.view_attachment a.file_attachment').attr('href','./apps/iportfolio/ajax.php?action=getStudentAssessmentFile&assessmentId='+assessmentId+'&studentId='+studentId);
					
					$('#tr_'+assessmentId).find('span.upload_time').html(res.modified_date+'<em> '+lang.editby1+res.modified_user+lang.editby2+'</em>');
					$('#tr_'+assessmentId).removeClass('attendanceabsent'); 
				} 
			});	
		});	
		$('div.table_row_tool a.delete_assessment_file').click(function(){
			studentId = $('#studentId').val();
			classId = $('#classId').val();
			var assessmentId = $(this).closest( "tr" ).attr("id").split('_')[1];
			if (confirm(lang.are_you_sure_to_delete)){
				kis.showLoading();
				$.post('apps/iportfolio/ajax.php?action=removeStudentAssessment', {assessmentId:assessmentId,classId:classId,studentId:studentId}, function(data){
					$('#td_attachment_'+assessmentId+' span.view_attachment').hide();
					$('#td_attachment_'+assessmentId+' span.view_attachment a.file_attachment').attr('href','');
					$('#td_attachment_'+assessmentId+' input.assessment_upload_btn').show();
					$('#tr_'+assessmentId).find('span.upload_time').html('-');
					$('#tr_'+assessmentId).addClass('attendanceabsent'); 
					kis.hideLoading();
				});  
			}
			return false;
		});				
    }, 
	
    teacher_class_list_init: function(){
		
    },   
	assessment_class_init: function(lang){
		var assessmentId = $("#assessmentId").val();
		var classId = $("#classId").val();
		var open_fancy_box = function(){
			$.fancybox( {
				'fitToView'    	 : false,	
				'autoDimensions'    : false,
				'autoScale'         : false,
				'autoSize'          : false,
				'width'             : 600,
				'height'            : 450,
				'padding'			 : 20,

				'transitionIn'      : 'elastic',
				'transitionOut'     : 'elastic',
				'href' : '#edit_box'				 
			});	 
		}	 
		kis.datepicker('#release_date');	
		$('#submitBtn').click(function(){
			update_aessessment_class();
		});			
		var checkAssessmentClassForm = function(){
			if(kis.hasEmptyInputElements($('#title'))){
				alert(lang.please_fill_in+lang.title);
				$('#title').focus();
				return false;
			}
			if(kis.hasEmptyInputElements($('#release_date'))){
				alert(lang.please_fill_in+lang.release_date);
				$('#release_date').focus();
				return false;
			}
				
			return true;
		}			
		var update_aessessment_class = function(){
			var title = $("form#assessmentForm input[name=title]").val();
			var release_date = $("form#assessmentForm input[name=release_date]").val();
			if(checkAssessmentClassForm()){
				kis.showLoading();
				$.post('apps/iportfolio/ajax.php?action=updateAssessment', {assessmentId:assessmentId,title:title,release_date:release_date}, function(data){
					parent.$.fancybox.close();
					kis.hideLoading().loadBoard();
				});  
			}
		}
		$('div.common_table_tool a.tool_edit').click(function(){
			open_fancy_box();
			return false;
		});
		$('div.table_row_tool a.delete_dim').click(function(){
			var studentId = $(this).closest( "td" ).attr("id").split('_')[2];
		
			kis.showLoading();
			$.post('apps/iportfolio/ajax.php?action=removeStudentAssessment', {assessmentId:assessmentId,classId:classId,studentId:studentId}, function(data){
				$('#td_attachment_'+studentId+' span.view_attachment').hide();
				$('#td_attachment_'+studentId+' span.view_attachment a.file_attachment').attr('href','');
				$('#td_attachment_'+studentId+' input.assessment_upload_btn').show();
				$('#tr_'+studentId).find('span.date_time').html('-');
				$('#tr_'+studentId).addClass('attendanceabsent'); 
				kis.hideLoading();
			});  
			return false;
		});		
		$('.assessment_upload_btn').each(function(i){
			var studentId = this.id.split('_')[2];
			kis.uploader({
				browse_button:  this.id,
				url: './apps/iportfolio/ajax.php?action=addStudentAssessment&assessmentId='+assessmentId+'&classId='+classId+'&studentId='+studentId,
				auto_start: true,
				multi_selection: false,
				onFilesAdded: function(up, files) {
					var uploader = this;
					$.each(files, function(i, file){
						$('#td_attachment_'+studentId).append('<a class="temp_file_attachment">'+file.name+'</a>').append($('<em class="progress">'));
						$('#td_attachment_'+studentId+' input.assessment_upload_btn').hide();
					});
				},
				onUploadProgress: function(up, file) {
					$('#td_attachment_'+studentId+' .progress').html(''+file.percent+'%');
				},
				onFileUploaded: function(up, file, info) {
				 	var res = $.parseJSON(info.response);
					$('#td_attachment_'+studentId+' a.temp_file_attachment').remove();
					$('#td_attachment_'+studentId+' em.progress').remove();
					$('#td_attachment_'+studentId+' span.view_attachment').show();
					$('#td_attachment_'+studentId+' span.view_attachment a.file_attachment').attr('href','./apps/iportfolio/ajax.php?action=getStudentAssessmentFile&assessmentId='+assessmentId+'&studentId='+studentId);
					
					$('#tr_'+studentId).find('span.date_time').html(res.modified_date+'<em> '+lang.editby1+res.modified_user+lang.editby2+'</em>');
					$('#tr_'+studentId).removeClass('attendanceabsent'); 
				} 
			});	
		});
			
    },
	teacher_list_init: function(lang){	
		var open_fancy_box = function(){
			$.fancybox( {
				'fitToView'    	 : false,	
				'autoDimensions'    : false,
				'autoScale'         : false,
				'autoSize'          : false,
				'width'             : 600,
				'height'            : 450,
				'padding'			 : 20,

				'transitionIn'      : 'elastic',
				'transitionOut'     : 'elastic',
				'href' : '#create_new_box'				 
			});	 
		}		
		var checkAssessmentForm = function(){
			if(kis.hasEmptyInputElements($('#title'))){
				alert(lang.please_fill_in+lang.title);
				$('#title').focus();
				return false;
			}
			if(kis.hasEmptyInputElements($('#release_date'))){
				alert(lang.please_fill_in+lang.release_date);
				$('#release_date').focus();
				return false;
			}
				
			return true;
		}		
		var save_assessment = function(){
			var type = $("form#assessmentForm input[name=type]").val();
			var action = (type=='new')?'saveAssessment':'updateAssessment';
			if(checkAssessmentForm()){
				kis.showLoading();
				$.post('apps/iportfolio/ajax.php?action='+action, $("form#assessmentForm").serialize(), function(data){
					parent.$.fancybox.close();
					kis.hideLoading().loadBoard();
				});
			}
		}	
		$('#submitBtn').click(function(){
			save_assessment();
		});	
		$('div.Content_tool a.new').click(function(){
			$("form#assessmentForm input[name=assessmentId]").val('');
			$("form#assessmentForm input[name=title]").val('');
			$("form#assessmentForm input[name=release_date]").val('');
			$("form#assessmentForm input[name=type]").val("new");
			$("form#assessmentForm select[name=classId]").prop('disabled', '');
			open_fancy_box();
			return false;
		});			
		$('div.table_row_tool a.edit_dim').click(function(){
			kis.showLoading();
			var assessmentId = $(this).closest('td').attr('id').split('_')[1];
			$.post('apps/iportfolio/ajax.php?action=getAssessmentById', {assessmentId:assessmentId}, function(data){
				$("form#assessmentForm input[name=assessmentId]").val(data.id);
				$("form#assessmentForm select[name=classId]").val(data.classId).prop('disabled', 'disabled');
				$("form#assessmentForm input[name=title]").val(data.title);
				$("form#assessmentForm input[name=release_date]").val(data.release_date);
				$("form#assessmentForm input[name=type]").val("edit");
				
				kis.hideLoading();
				open_fancy_box();   
			},'json'); 
			return false;
		});	
		$('div.table_row_tool a.copy_dim').click(function(){
			kis.showLoading();
			var assessmentId = $(this).closest('td').attr('id').split('_')[1];
				$.post('apps/iportfolio/ajax.php?action=copyAssessment', {assessmentId:assessmentId}, function(data){
					kis.hideLoading().loadBoard();
				});
			return false;
		});			
		$('div.table_row_tool a.delete_dim').click(function(){
			var assessmentId = $(this).closest('td').attr('id').split('_')[1];
			var title = $('#title_'+assessmentId).text();
			var msg = lang.message+lang.remove+lang.assessment+title+'?';
			 if (confirm(msg)){
				kis.showLoading();
				$.post('apps/iportfolio/ajax.php?action=removeAssessment', {assessmentId:assessmentId}, function(data){
					kis.hideLoading().loadBoard();
				});
			} 
			return false;
		});			
		kis.datepicker('#release_date');		
	} ,	
	parent_assessment_list_init: function(){
		$('.assessment_link').click(function(){
			$(this).find('img').fadeOut();
		});	
    },	
    sbs_init: function(){
		$('div.common_table_tool a.tool_edit, .phaseTitle').click(function(){
			var parentId = $(this).closest('tr').attr('id').split('_')[1];
			var assignmentId = $(this).closest('tr').attr('id').split('_')[2];
			kis.showLoading();
			$.post('apps/iportfolio/ajax.php?action=editSBSContent', {parentId:parentId,assignmentId:assignmentId}, function(data){
				$('.sbs_edit_box_'+parentId).each(function(i){
					$(this).html(data[i]);
				});
				kis.hideLoading();
				 $.fancybox.open($('.sbs_edit_box_'+parentId),{
					'fitToView'    	 : false,	
					 'autoDimensions'    : false,
					 'autoScale'         : false,
					 'autoSize'          : false,
					 'width'             : 800,
					 'height'            : 600,
					 'padding'			 : 20,
					 'transitionIn'      : 'elastic',
					 'transitionOut'     : 'elastic'
					 
				});
				$('.fancybox-skin').css('background-color', '#fcf1d5');
			}, 'json');
			return false;
		});

    } ,
	sbs_submit_form: function(assignmentId){
		var ans_str = '';
		var has_ans=false, cur_idx='';
		var delimiter;
		$("form#ansForm_"+assignmentId+" .sbs_answer").each(function(i){
			var qType = this.name.split('_')[0];
			var ans_idx = qType+this.name.split('_')[2];
			var this_ans = '';
			if(ans_idx!=cur_idx){
				ans_str += '#ANS#';
				cur_idx = ans_idx;
				has_ans = false;
			}
			switch(qType){
				case 'F': //General
					if(this.type=='checkbox'||this.type=='radio'){
						if(this.checked){
							if(has_ans)
								this_ans += ',';
							this_ans += $(this).val();
							has_ans = true;
						}
					}else{
						if(has_ans)
							this_ans += ',';
						this_ans += $(this).val();
						has_ans = true;
					}
					break;
				case 'RS': //Likert Scale or Table-like
						if(this.type=='text'){
							this_ans += '#MQA#';
							this_ans += $(this).val();
						}else{
							if(this.checked){
								if(has_ans)
									this_ans += ',';
								this_ans += $(this).val();
								has_ans = true;
							}
						}
					break;
				case 'NA': //Not Applicable
					break;
			}
			ans_str += this_ans;
		});
		var p_id = $("form#ansForm_"+assignmentId+" input[id=parent_id]").val(); 
		var h_id = $("form#ansForm_"+assignmentId+" input[id=handin_id]").val(); 
 		$.post('apps/iportfolio/ajax.php?action=saveSBSContent', {p_id:p_id,a_id:assignmentId,h_id:h_id,ans_str:ans_str}, function(data){
			parent.$.fancybox.close();
		}); 
	}
		
			
}

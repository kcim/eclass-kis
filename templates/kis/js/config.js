KIS_TITLE = ":: eClass KIS ::";
KIS_USERTYPE_TEACHER = 'T';
KIS_USERTYPE_STUDENT = 'S';
KIS_USERTYPE_PARENT  = 'P';
KIS_DATEPICKER_IMAGE_URL = "/images/kis/icon_calendar_off.gif";
KIS_JS_ROOT = "/templates/kis/js/";
KIS_UPLOADER_URL = '/templates/jquery/plupload/';
KIS_EDITOR_URL = '/templates/html_editor/'
KIS_CONFIRM_DEFAULT_PARAMS = {content:'', target:window, callback:false};
KIS_UPLOADER_DEFAULT_PARAMS = {
    runtimes : 'gears,html5,flash,silverlight,browserplus',
    browse_button : 'uploader_button',
    multipart_params: {},
    multi_selection: true,
    drop_element: 'attach_file_area',
    max_file_size: '500mb',
    url : '.',
    flash_swf_url : KIS_UPLOADER_URL+'plupload.flash.swf',
    silverlight_xap_url : KIS_UPLOADER_URL+'plupload.silverlight.xap',
}
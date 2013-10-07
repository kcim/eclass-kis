kis.epayment = {
    
    transactionrecord_init: function(){
	$(function(){
	    $('#table_filter select[name="recent_days"]').change(function(){
	    
	    if ($(this).val()=='custom'){
		$('#table_filter .select_period').fadeIn();
	    }else{
		$('#table_filter .select_period').hide();
	    }
		
	    });
	    
	    kis.datepicker('input[name="from_date"]').datepicker("option", "onClose", function(selectedDate){
		$("input[name='to_date']").datepicker("option", "minDate", selectedDate);
	    });
	    kis.datepicker('input[name="to_date"]').datepicker("option", "onClose", function(selectedDate){
		$("input[name='from_date']").datepicker("option", "maxDate", selectedDate);
	    });
	 
	});

    } 
 
}
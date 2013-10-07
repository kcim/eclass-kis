<script>
$(function(){
   
    <? if (!$unpaid_count): ?>
    $('#module_page .module_tab span .tab_paymentrecords').append('<em class="tabletextrequire"> (<?=$unpaid_count?>)</em>');
    <? endif; ?>
    
});
</script>
<div class="epayment_balance">
    <em>$ <?=$account_stat['balance']?></em>
    <div class="balance_title"><h1> <?=$kis_lang['accountbalance']?> : </h1><span class="date_time">(<?=$kis_lang['lastupdated']?>: <?=$account_stat['updated']?$account_stat['updated']:' -- '?>)</span></div>	
    <p class="spacer"></p>
</div>
<div class="main_content">

<? kis_ui::loadModuleTab(array('transactionrecords', 'paymentrecords', 'addvaluerecords'), $q[0], "#/apps/epayment/");?>
<? kis_ui::loadTemplate($kis_data['main_template'], $kis_data); ?>
</div>
<div class="mail_board" id="content_board_frame">
    <div class="mail_usage">
	<div class="mail_usage_bar"><span style="right:<?=100-$used_percent?>px;"></span><em><?=$used_percent?>%</em></div>
	<div class="mail_usage_text"><?=$kis_lang['used']?>:  <em><?=round($kis_data['used_quota'],2)?></em>/ <?=round($kis_data['total_quota'],2)?> MB</div>
    </div>
    <? kis_ui::loadTemplate('menu', $kis_data);?>
    
    <div class="mail_list">
	<? kis_ui::loadTemplate($kis_data['main_template'], $kis_data);?>
	<p class="spacer"></p>
    </div>
    
    <p class="spacer"></p>
                    
</div>
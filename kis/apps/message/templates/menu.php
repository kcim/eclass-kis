<script>kis.message.mail_menu_init(<?=!$tab||$tab=='inbox'?1:0?>);</script>

<div class="mail_menu">
    <ul>
	<li <?=$tab == 'compose'? 'class="selected"':''?>><a class="mail_create" href="#/apps/message/compose/"><?=$kis_lang['compose']?></a></li>
	<li class="mail_line"></li>
	<li <?=!$tab || $tab == 'inbox'? 'class="selected"':''?>><a class="mail_inbox" href="#/apps/message/"><?=$kis_lang['inbox']?>
	
	<span><?=$inbox_mail_count?"($inbox_mail_count)":""?></span>
	<input class="mail_refresh" type="button" title="<?=$kis_lang['refresh']?>"></a>
	</li>
	<li <?=$tab == 'sent'? 'class="selected"':''?>><a class="mail_sent" href="#/apps/message/sent/"><?=$kis_lang['sent']?></a></li>
	<li <?=$tab == 'draft'? 'class="selected"':''?>><a class="mail_draft" href="#/apps/message/draft/"><?=$kis_lang['draft']?>
	<span><?=$draft_mail_count?"($draft_mail_count)":""?></span></a>
	</li>
	<li <?=$tab == 'trash'? 'class="selected"':''?>><a class="mail_trash" href="#/apps/message/trash/"><?=$kis_lang['trash']?></a></li>
	<li class="mail_line"></li>
	<li <?=$tab == 'settings'? 'class="selected"':''?>><a class="mail_setting" href="#/apps/message/settings/"><?=$kis_lang['settings']?></a></li>
      
    </ul>
    
</div>
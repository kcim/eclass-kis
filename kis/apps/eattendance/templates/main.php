<? if ($kis_user['type']==kis::$user_types['teacher']) : ?>

<div class="content_board_menu">
<? kis_ui::loadLeftMenu(array('takeattendance','','monthlyrecord','','reminderrecord'), $q[0], "#/apps/eattendance/");?>
    <div class="main_content">
    <? kis_ui::loadTemplate($main_template, $kis_data); ?>
    </div><p class="spacer"></p>
</div>
		
<? else: ?>

<div class="main_content">
<? kis_ui::loadTemplate($main_template, $kis_data); ?>
</div> <p class="spacer"></p>

<? endif; ?>
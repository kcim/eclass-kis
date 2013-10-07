<style>
.fancybox-skin	{background-color:#fcf1d5;}
</style>
<? if ($kis_user['type']==kis::$user_types['teacher']) : ?>
<script>
$(function(){
    kis.iportfolio.init();
});		
</script>

<div class="content_board_menu">
    
    <? kis_ui::loadLeftMenu(array('studentaccount','schoolrecords','assessmentreport','sbs','learningportfolio',''), $q[0], '#/apps/iportfolio/'); ?>

    <div class="main_content">
    <? kis_ui::loadTemplate($main_template, $kis_data);?>
    </div>
    <p class="spacer"></p>

</div>

<? elseif ($kis_user['type']==kis::$user_types['parent']) : ?>

<div class="content_board_menu content_board_menu_ipf">
    
    <? kis_ui::loadLeftMenu(array('information','schoolrecords','assessmentreport','sbs','learningportfolio'), $q[0], '#/apps/iportfolio/', $kis_data['student_info']['photo']); ?>

    <div class="main_content">
    <? kis_ui::loadTemplate($main_template, $kis_data);?>
    </div>
    <p class="spacer"></p>

</div>

<? endif ;?>
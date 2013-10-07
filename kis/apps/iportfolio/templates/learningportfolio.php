<script>
$(function(){
    kis.iportfolio.learningportfolio_init();
});
</script>
<style>
.fancybox-skin {background-color:#f9f9f9}
</style>
<link href="/templates/kis/css/learningportfolio.css" rel="stylesheet" type="text/css">
<div id="fb-root"></div>

<?php 
	list($page,$amount,$total,$sortby,$order) = $kis_data['PageBar']; 
	$lpCnt = count($kis_data['learningportfolio_record']);
?>
    <? if ($lpCnt): ?>
    <?
	    for($r=0;$r<$lpCnt;$r++){
		    $_record = $kis_data['learningportfolio_record'][$r];
		    $_title = $_record['title'];
		    $_commentCnt = $_record['comments_count'];
		    $_theme = $_record['theme'];
		    $_publishedDays = $_record['published_days'];
		    $_modified = $_record['modified'];
		    $_modifiedDays = $_record['modified_days'];
		    $_shareURL = $_record['share_url'];
		    $_version = $_record['version'];
		    $_published = $_record['published'];
		    $_withinTime = $_record['within_time'];
		    $_key = $_record['key'];
		    
    ?>				
				    <div class="lp_list">
					<ul>
					  <li class="theme_<?=$_published? $_theme.' lp_status_published':$_theme?>">
							<div class="lp_content">
							<div class="lp_theme"></div>
							</div>
							<div class="lp_info">
								  <h1><?=$_title?></h1>
								<p class="spacer"></p>
							<div class="lp_edit_tool">
							<?php if($_withinTime){ ?>
								<a href="http://<?=$kis_data['eclass40_httppath']?>src/iportfolio/?mode=draft&portfolio=<?=$_key?>" class="tool_edit" target="_blank"><?=$kis_lang['edit']?></a>
							<?php } ?>
								<a rel="portfolio_publish" class="fancybox tool_publish" href="/home/portfolio/learning_portfolio_v2/ajax.php?action=getPublish&portfolio=<?=$_key?>"><?=$kis_lang['LearningPortfolio']['Publish']?></a>
							</div>
							<p class="spacer"></p>
							<div class="lp_publish_info">
							<?php if($_published){ ?> 
								<span class="lp_info_draft"><?=$kis_lang['LearningPortfolio']['LastModified']?> <?=$_modifiedDays?></span>
								<div class="lp_publish_date">
									<a class="lp_info_published" href="http://<?=$kis_data['eclass40_httppath']?>src/iportfolio/?mode=publish&portfolio=<?=$_key?>" target="_blank"><?=$kis_lang['LearningPortfolio']['Published']?></a><span class="date_time" title="<?=$_published?>"> <?=$_publishedDays?></span>  
									<a rel="portfolio_comment" class="lp_info_comment fancybox_iframe" href="/home/portfolio/learning_portfolio/browse/student_comment.php?key=<?=$_key?>"><?=$kis_lang['LearningPortfolio']['Comments']?> <em>(<?=$_commentCnt?>)</em>&nbsp;</a> 
								</div>
							<?php }elseif($_modified){ ?> 
								<div class="lp_publish_date"><span><?=$kis_lang['LearningPortfolio']['Drafted']?> </span>
									<span class="date_time" title="<?=$_modified?>"> <?=$_modifiedDays?></span>
								</div>
							<?php } ?> 							
							</div> 
							  <p class="spacer"></p>
								<? if ($_published): ?>
									<div class="lp_ref">
										<div class="lp_fb_like">
											<div class="fb-like" data-href="<?=$_shareURL?>" data-send="false" data-action="like" data-font="arial" data-layout="standard" data-colorscheme="light" data-width="300" data-height="20" data-show-faces="false"></div>
										</div>
									</div>		
								<? endif; ?>                                               
							</div> 
										
					   </li>
					</ul>
                </div>
<?php
	}
?>
<? else: ?>
    <? kis_ui::loadNoRecord() ?>
<? endif; ?>
<p class="spacer"></p>
<? kis_ui::loadPageBar($page, $amount, $total, $sortby, $order) ?> 
<p class="spacer"></p><br/>
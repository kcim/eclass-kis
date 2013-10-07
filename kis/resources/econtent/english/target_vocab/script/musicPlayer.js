/* 
Author : Stanley
The bgm player has not tested.
There may be some bug bgm player.
*/
(function($){
	function detectPlugin() { 
		// allow for multiple checks in a single pass 
		var daPlugins = detectPlugin.arguments; 
		// consider pluginFound to be false until proven true 
		var pluginFound = false; 
		// if plugins array is there and not fake 
		if (navigator.plugins && navigator.plugins.length > 0) { 
			var pluginsArrayLength = navigator.plugins.length; 
			// for each plugin... 
			for (pluginsArrayCounter=0; pluginsArrayCounter < pluginsArrayLength; pluginsArrayCounter++ ) { 
				// loop through all desired names and check each against the current plugin name 
				var numFound = 0; 
				for(namesCounter=0; namesCounter < daPlugins.length; namesCounter++) { 
					// if desired plugin name is found in either plugin name or description 
					if( (navigator.plugins[pluginsArrayCounter].name.indexOf(daPlugins[namesCounter]) >= 0) || (navigator.plugins[pluginsArrayCounter].description.indexOf(daPlugins[namesCounter]) >= 0) ) { 
					// this name was found 
						numFound++; 
					} 
				} // now that we have checked all the required names against this one plugin, 
				// if the number we found matches the total number provided then we were successful 
				if(numFound == daPlugins.length) { 
					pluginFound = true; 
					// if we've found the plugin, we can stop looking through at the rest of the plugins
					break; 
				}
			} 
		} 
		return pluginFound; 
	} 
	
	// Define variable
	var bgmPlayerObj=null;
	var isIE = navigator.appName == 'Microsoft Internet Explorer';
	var hasWinMediaPlayer = detectPlugin('Windows Media Player');
	var hasQuickTimePlayer = detectPlugin('QuickTime');
    
    function getPlayer(path,autoStart,swf){
		var start = (autoStart==null?1:autoStart);
		var player = '';

		// window media player
		var a = '<object type="video/x-ms-wmv" style="width:0px; height:0px;" >\n  <param name="src" value="';
		var b = '" valuetype="ref">\n  <param name="autoStart" value="'+start+'" valuetype="data">\n  <param name="Volume" value="100" valuetype="data">\n  <embed src="';
		var c ='" type="video/x-ms-wmv" autoStart="'+start+'" showControls="0" volume="100" style="width:0px; height:0px; visibility:hidden;"></embed>\n  </object>';

		//QuickTime player	 
		var autoplay = start? 'true':'false'
		var d = '<object width="0" height="0">\n <param name="src" value="';
		var e = '"><param name="autoplay" value="'+autoplay+'">\n <param name="controller" value="false">\n <param name="bgcolor" value="#FF9900">\n <embed src="';
		var f = '" autostart="'+autoplay+'" loop="false" width="0" height="0" controller="false" bgcolor="#FF9900"></embed>\n </object>';
		
		// Google Player
		var g = '<embed type="application/x-shockwave-flash" src="http://www.google.com/reader/ui/3523697345-audio-player.swf?audioUrl=';
		var h = '&autoPlay='+autoplay+'" width="0" height="0" allowscriptaccess="never" quality="best" bgcolor="#ffffff" wmode="window" flashvars="playerMode=embedded" />';
	
		// eclass player
		var i = '<embed src="';//swf
		var j = '?SoundFile=';//sound
		var k = '&autoStart='+start+'&playMaxTime=999&SoundFile2=&playMaxTime=999" quality="high" bgcolor="#ffffff" width="1" height="1" name="alarm" align="middle" wmode="Transparent" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /> ';
		
		if (swf != null && swf != '' ){
			player = i + swf + j + path + k; //eclass player
		}
		else{		
			if (isIE){		
				player = a + path + b + path + c; //window media player
			}
			else{
				if (hasWinMediaPlayer){
					 player = a + path + b + path + c; //window media player
				}
				else if (hasQuickTimePlayer){
					player = d + path + e + path + f; //quick time player
				}
				else{
					player = g + path + h; //google player
				}
			}
		}

		return  player;
    }
    
    function bgmPlayer(setting){
		bgmPlayerObj = this;
		setting = (setting==null || setting=='')? {}:setting;

		var playingMode = (setting.playingMode==null||setting.playingMode=='')? 1: setting.playingMode;

		var RestSec = (setting.RestSec==null||setting.RestSec=='')? 3: setting.RestSec;

		var playWhileloading = (setting.playWhileloading==null||setting.playWhileloading=='')? false: setting.playWhileloading;

		var songPlaying = false;
		var id = [];
		var songTitle = [];
		var songTime = [];
		var songPath = [];
		var timer;
		var nextTrack;
		var track;

		function getRandomTrack(){
		 return parseInt(Math.random()*id.length);
		}

		this.listTrack = function (path, title, mins, sec){
			 if (title == null || title == '' ) { title1 = 'Track ' + (id.length + 1); } else { title1 = title; }
			 if (mins == null || mins == '' ) { mins1 = 00; } else { mins1 = mins; }
			 if (sec == null || sec == '' ) { sec1 = 30; } else { sec1 = sec; }

			time = mins1 * 60 + sec1 * 1;

			 id.push(path);
			 songTitle.push(title1);
			 songTime.push(time);
			 songsRandom = getRandomTrack();
			 return this;
		}

		this.bgm_in=function(e){
			 if(songPlaying) {
				 clearTimeout(timer);
			 }
			 songPlaying = true;
			 track = e;

			 if(track > id.length){
				 track = 0;
			 }
			 if(track < 0){
				 track = id.length - 1;
			 }

			 var player = getPlayer(id[track]);

			 this.bgm_out(player);

			 var time1 = songTime[track] * 1000 + RestSec * 1000;
			 timer = setTimeout(function(){
				 var player = $.getBGMPlayer();
				 player.bgm_in(player.bgm_next(track));
			 },time1);
			 return this;
		}

		this.bgm_out = function(f){
		  //$('#jsBgmPlayer').empty().html(f);
			 if (f == 'stop'){
				 window.frames['jsBgmPlayer'].src = 'empty.html';
			 }else{
				 document.getElementById('jsBgmPlayer').contentWindow.document.write(f)
			 }
			 return this;
		}

		this.bgm_stop=function(){   		 
			 this.bgm_out('stop');
			 clearTimeout(timer);
			 songPlaying = false;
			 return this;
		}

		this.play=function(){
			 if(songPlaying){
				 return this;
			 }
			 else{
				 this.bgm_in(getRandomTrack());
			 }
			 return this;
		}

		this.togglePlay = function(){
			 if(songPlaying){
				 this.bgm_stop();
			 }
			 else{
				 this.play();
			 }
			 return this;
		}

		this.backward= function(){
			 if(!songPlaying){
			   return this;
			 }
			 else{
			   this.bgm_in(track-1);
			 }
			 return this;
		}

		this.forward=function(){
			 if(!songPlaying){
				 //alert(nthsPlaying);
				 return this;
			 }
			 else{
				 this.bgm_in(track+1);
			 }
			 return this;
		}

		this.bgm_next=function(track){
			 var next_track;
			 if(playingMode == 0) {
				 next_track = track + 1;
			 }                       	 
			 if(playingMode == 1) {
				 next_track= getRandomTrack()
			 }    
			 if(nextTrack == track && id.length > 1) {
				 this.bgm_next(track);
			 }               	 
			 if(id.length == 1) {  
				 next_track = track;
			 } 	 
			 return next_track;
		}
   	 
    }

    $.getBGMPlayer=function(setting){
		 $('#jsBgmPlayer').remove();
		 //$(document.body).append('<div id="jsBgmPlayer" style="overflow:hidden;width:0px;height:0px;" ></div>');
		 $(document.body).append('<iframe id="jsBgmPlayer" name="jsBgmPlayer" style="overflow:hidden;width:0px;height:0px;" src="empty.html"></iframe>');
		 bgmPlayerObj = (bgmPlayerObj==null?new bgmPlayer(setting):bgmPlayerObj);   	 
		 return bgmPlayerObj;
    }


    $.fn.insertPlayer = function (path,autoStart,swf){
		return this.empty().html(getPlayer(path,autoStart,swf));
    }
})(jQuery);
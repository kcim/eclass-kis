/* kinder_parent_07_content_portal.html */
function ShowList(IsShow) {
	if (document.getElementById("id_panel_left").style.display == "none") {
		document.getElementById("id_panel_left").style.display = "block";
		document.getElementById("id_panel_right").style.marginLeft = "180px";
		document.getElementById("id_list_ctrl").className = "right_icon_select";
	} else {
		document.getElementById("id_panel_left").style.display = "none";
		document.getElementById("id_panel_right").style.marginLeft = "0px";
		document.getElementById("id_list_ctrl").className = "right_icon";
	}
}

/* kinder_parent_07_content_chi_stroke.htm */
var store;

function ListenClick() {
	document.getElementById("id_sound").play();
}
function ChangeAudio() {
	var audio = document.getElementById("id_sound");
	audio.src = "../soundClip/snd_02.wav";
}
function WriteClick() {
	document.getElementById("id_stroke_frame").src = "chi_stroke_sample/stroke_interaction.html?type=" + String(store.type) + "&index=" + String(store.index);
	//document.getElementById("id_stroke_frame").src = "chi_stroke_sample/stroke_interaction.html";
	document.getElementById("id_btn_reset").style.visibility = "hidden";
	document.getElementById("id_btn_watch").style.display = "block";
}
function ReplayClick() {
	document.getElementById("id_stroke_frame").src = "chi_stroke_sample/stroke_animation.html?type=" + String(store.type) + "&index=" + String(store.index);
	//document.getElementById("id_stroke_frame").src = "chi_stroke_sample/stroke_animation.html";
}
function WatchClick() {
	document.getElementById("id_stroke_frame").src = "chi_stroke_sample/stroke_animation.html?type=" + String(store.type) + "&index=" + String(store.index);
	//document.getElementById("id_stroke_frame").src = "chi_stroke_sample/stroke_animation.html";
	document.getElementById("id_btn_reset").style.visibility = "visible";
	document.getElementById("id_btn_watch").style.display = "none";
}




var QueryString = function () {
	// This function is anonymous, is executed immediately and 
	// the return value is assigned to QueryString!
	var query_string = {};
	var query = window.location.search.substring(1);
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		// If first entry with this name
		if (typeof query_string[pair[0]] === "undefined") {
			query_string[pair[0]] = pair[1];
			// If second entry with this name
		} else if (typeof query_string[pair[0]] === "string") {
			var arr = [ query_string[pair[0]], pair[1] ];
			query_string[pair[0]] = arr;
			// If third or later entry with this name
		} else {
			query_string[pair[0]].push(pair[1]);
		}
	}
	return query_string;
} ();
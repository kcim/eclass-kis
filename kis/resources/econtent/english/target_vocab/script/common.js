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
function ListenClick() {
	document.getElementById("id_sound").play();
}
function ChangeAudio() {
	var audio = document.getElementById("id_sound");
	audio.src = "../soundClip/snd_02.wav";
}
function WriteClick() {
	document.getElementById("id_stroke_frame").src = "chi_stroke_sample/stroke_interaction.html";
	document.getElementById("id_btn_reset").style.visibility = "hidden";
	document.getElementById("id_btn_watch").style.display = "block";
}
function ReplayClick() {
	document.getElementById("id_stroke_frame").src = "chi_stroke_sample/stroke_animation.html";
}
function WatchClick() {
	document.getElementById("id_stroke_frame").src = "chi_stroke_sample/stroke_animation.html";
	document.getElementById("id_btn_reset").style.visibility = "visible";
	document.getElementById("id_btn_watch").style.display = "none";
}
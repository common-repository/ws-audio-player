function init() {
	tinyMCEPopup.resizeToInnerSize();
}

function getCheckedValue(radioObj) {
	if(!radioObj)
		return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked)
			return radioObj.value;
		else
			return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

function insertWSAPLink() {

	var tagtext;

	var wsap = document.getElementById('wsap_panel');

	// who is active ?
	if (wsap.className.indexOf('current') != -1) {
		var MusicURL = document.getElementById('MusicURL').value;
		var MusicTitle = document.getElementById('MusicTitle').value;
		var MusicDownload = document.getElementById('MusicDownload').value;
		if ((MusicURL != '') && MusicURL != 'http://'){

            var search=new Array(/\'/g,/\ /g);
            var replace=new Array("&#39;","%20");
            for(var i=0;i<MusicURL.length;i++){
                MusicURL=MusicURL.replace(search[i],replace[i]);
            }
            //alert(MusicURL);
            if(MusicTitle==''){
                if (MusicDownload!='on')
                    MusicTitle='';
                else
                    MusicTitle=',';
            }else{
                MusicTitle=',' + MusicTitle;
            }

            if (MusicDownload!='on')
                MusicDownload='';
            else
                MusicDownload=',download';

			tagtext = "[audio="+ MusicURL + MusicTitle + MusicDownload + "]";
		}else{
			tinyMCEPopup.close();
        }
	}

	if(window.tinyMCE) {
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
		//Peforms a clean up of the current editor HTML.
		//tinyMCEPopup.editor.execCommand('mceCleanup');
		//Repaints the editor. Sometimes the browser has graphic glitches.
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	}
	return;


}

<?php

require_once('php/includes.php');

session_start();

$_SESSION["DM_AUTH_UPLOAD"] = current_user_can('upload_files');

$DM_UPLOAD_DIRECTORY = dm_getuploaddirectory();

$DM_RESOURCE_ROOT = get_bloginfo('home') . '/wp-content/plugins/dm-albums/';

$DM_SLIDE_SORTER_TILE_SIZE_W = 200;
$DM_SLIDE_SORTER_TILE_SIZE_H = 150;

$dm_existing_albums = dm_get_album_list($DM_UPLOAD_DIRECTORY);

$MAX_UPLOAD_SIZE = ((int) ini_get('upload_max_filesize')) * 1024;

if($MAX_UPLOAD_SIZE <= 0 || empty($MAX_UPLOAD_SIZE) || !isset($MAX_UPLOAD_SIZE))	$MAX_UPLOAD_SIZE = 102400;

?>

<link rel="stylesheet" type="text/css" href="<?php print($DM_RESOURCE_ROOT); ?>ui/swfupload.css" />

<script type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>javascript/browser.js"></script>

<script type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>flash/swfupload.js"></script>

<script type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>javascript/swfupload.queue.js"></script>

<script type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>javascript/fileprogress.js"></script>

<script type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>javascript/handlers.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>slidesorter/core.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>slidesorter/events.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>slidesorter/css.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>slidesorter/coordinates.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>slidesorter/drag.js"></script>

<script language="JavaScript" type="text/javascript" src="<?php print($DM_RESOURCE_ROOT); ?>slidesorter/dragsort.js"></script>

<script type="text/javascript">
<!--
/* <![CDATA[ */

var g_dm_Browser = new Browser();
var g_dm_UploadDir = "<?php print($DM_UPLOAD_DIRECTORY);?>";
var g_FATAL_ERROR = false;

//*************************************
// FLASH UPLOAD

var g_dm_UPLOAD_IN_PROGRESS = false;
var g_dm_PHP_SESSION_ID = "<?php echo session_id(); ?>";

var dm_albums_swfu;

function isUrl(s)
{
	if(s == "") return true;

	var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
	return regexp.test(s);
}

function dm_InitializeSWFUpload()
{

	dm_UpdatePanelPosition();

	document.getElementById("dm_upload_buttons_container").innerHTML = '<span id="spanButtonPlaceHolder" style="padding: 0px; margin: 0px;"></span><input id="btnCancel" type="button" class="button" style="margin-top: 5px;" value="Cancel All" onclick="dm_albums_swfu.cancelQueue();" disabled="disabled" /><br/><input id="btnStart" type="button" class="button-primary" style="margin-top: 10px; margin-left: 11px;" value="Start Upload" onclick="dm_StartUpload();" disabled="disabled" />';

	refreshQueue();


	var settings = {
		flash_url : "<?php print($DM_RESOURCE_ROOT); ?>flash/swfupload.swf",
		upload_url: "<?php print($DM_RESOURCE_ROOT); ?>wp-dm-upload.php",
		file_size_limit : "<?php print($MAX_UPLOAD_SIZE); ?>",
		file_types : "*.jpg;*.gif;*.png",
		file_types_description : "Image Files",
		post_params: {"PHPSESSID" : g_dm_PHP_SESSION_ID},
		file_upload_limit : 0,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,

		// Button settings
		button_image_url: (g_dm_Browser.Exploiter ? "<?php print($DM_RESOURCE_ROOT); ?>ui/browse_button_ie.gif" : "<?php print($DM_RESOURCE_ROOT); ?>ui/browse_button.gif?moddate=20120105"),
		button_width: "72",
		button_height: "23",
		button_placeholder_id: "spanButtonPlaceHolder",
		button_text_left_padding: 1,
		button_text_top_padding: 1,

		// The event handler functions are defined in handlers.js
		file_queued_handler : dm_fileQueued,
		file_queue_error_handler : dm_fileQueueError,
		file_dialog_complete_handler : dm_fileDialogComplete,
		upload_start_handler : dm_uploadStart,
		upload_progress_handler : dm_uploadProgress,
		upload_error_handler : dm_uploadError,
		upload_success_handler : dm_uploadSuccess,
		upload_complete_handler : dm_uploadComplete,
		queue_complete_handler : dm_queueComplete	// Queue plugin event
	};

	dm_albums_swfu = new SWFUpload(settings);

	setTimeout('document.getElementById("div_dm_uploader").style.display = ""', 5);
	setTimeout('document.getElementById("p_dm_bottom_help").style.display = ""', 5);
	setTimeout('document.getElementById("dm_albums_manager_container").style.display = ""', 5);

	dm_FinishUpload();
}

function dm_albums_UpdateAlbumList(orderby)
{
	var timestamp = new Date();

	dm_albums_AjaxRequest('<?php print($DM_RESOURCE_ROOT); ?>wp-dm-albums-ajax.php?PHPSESSID=' + g_dm_PHP_SESSION_ID + '&orderby=' + orderby + "&time=" + timestamp.getTime());

	if(orderby == "alpha")
	{
		document.getElementById("img_dm_sort_name").src = "<?php print($DM_RESOURCE_ROOT); ?>ui/sort_name_on.gif";
		document.getElementById("img_dm_sort_newest").src = "<?php print($DM_RESOURCE_ROOT); ?>ui/sort_newest.gif";
	}
	else
	{
		document.getElementById("img_dm_sort_name").src = "<?php print($DM_RESOURCE_ROOT); ?>ui/sort_name.gif";
		document.getElementById("img_dm_sort_newest").src = "<?php print($DM_RESOURCE_ROOT); ?>ui/sort_newest_on.gif";
	}
}

function dm_albums_RefreshAlbums(httpRequest)
{
	if (httpRequest.readyState == 4)
	{
        if (httpRequest.status == 200)
        {
            var xmldoc = httpRequest.responseXML;

        	var albums = xmldoc.getElementsByTagName("album");

        	var albumHTML = '<table cellpadding="0" cellspacing="0" border="0" width="98%">';

        	if(g_dm_Browser.Exploiter)	albumHTML = '<table cellpadding="0" cellspacing="0" border="0" width="93%">';

        	for(var i = 0; i < albums.length; i++)
			{
				var album = albums[i];

				var name = album.getElementsByTagName("name").item(0).firstChild.data;
				var directory = album.getElementsByTagName("directory").item(0).firstChild.data;

				albumHTML += '<tr class="dm_existing_item">';
				albumHTML += '<td><div style="margin-left: 5px; margin-top: 0px; margin-bottom: 0px; margin-right: 0px;  padding-right: 0px;"><p><a href="#" onClick="dm_UpdateAlbum(\'' + name + '\');"  class="dm_album_link">' + name + '</a></p><p><a href="#" onClick="javascript:dm_DisplayDetailManager(\'' + name + '\');" class="dm_detail_link">&raquo; Manage Album Details</a></p><p><a href="#" onClick="javascript:dm_DisplayAlbumSorter(\'' + name + '\');" class="dm_detail_link">&raquo; Photo Sorter</a></p></div></td>';
				albumHTML += '<td align="center"><a class="preview button" style="margin-right: 5px" href="#" onClick="return dm_InsertAlbum(\'' + directory + '\', \'' + name + '\');" title="Insert Album \'' + name + '\' Into Post">Insert</a></td>';
				albumHTML += '<td align="center"><a style="margin-right: 5px" href="#" onClick="return dm_DeleteAlbum(\'' + directory + '\', \'' + name + '\');"><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/delete.gif" border="0"/></a></td>';
				albumHTML += '</tr>';
				//albumHTML += '<tr><td colspan="3"><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/spacer.gif" height="5"/></td></tr>';
			}

			if(albums.length == 0)
			{
				albumHTML += '<tr>';
				albumHTML += '<td><p><small>There are no existing albums in your DM Albums&#153; album folder.  Create your first album by uploading photos into an album in the <b>Update Existing or Add New Album</b> section below.  For more information read the <a href="#" onClick="dm_ShowHideHelp();">help</a> section. </small></p></td>';
				albumHTML += '</tr>';
				albumHTML += '<tr><td><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/spacer.gif" height="5"/></td></tr>';
			}

        	albumHTML += '</table>';

			document.getElementById("dm_ExistingAlbums").innerHTML = albumHTML;
        }

        else
        {
        	document.getElementById("dm_ExistingAlbums").innerHTML = '<p>A problem was encountered while refereshing the existing DM Albums list.  Please try again.</p>';
        }
    }
}

function dm_albums_AjaxRequest(url) {
    var httpRequest;

    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType) {
            httpRequest.overrideMimeType('text/xml');
            // See note below about this line
        }
    }
    else if (window.ActiveXObject) { // IE
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {}
        }
    }

    if (!httpRequest) {
        alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }
    httpRequest.onreadystatechange = function() { dm_albums_RefreshAlbums(httpRequest); };
    httpRequest.open('GET', url, true);
    httpRequest.send('');

}

function dm_DeleteAlbum(path, name)
{
	if(confirm("Are you sure you want to permanantly delete the album '" + name + "'?\nThis will delete all photos and captions in the album.\n\n'Cancel' to stop, 'OK' to delete."))
	{
		dm_albums_AjaxRequest('<?php print($DM_RESOURCE_ROOT); ?>wp-dm-albums-ajax.php?delete_album=' + name);
		dm_HideDetailManager();
	}
}

function dm_InsertAlbum(path, name)
{
	var dm_album_app = "<?php print(get_option('DM_PHOTOALBUM_APP')); ?>";
	var dm_album_currdir = "<?php print(str_replace(get_option('DM_HOME_DIR'), "", $DM_UPLOAD_DIRECTORY)); ?><?php print(dm_user_uploaddirectory()); ?>" + name;

	if(dm_album_currdir.lastIndexOf("/") != dm_album_currdir.length)	dm_album_currdir += "/";

	//var text = "[album: " + dm_album_app + "?currdir=/" + dm_album_currdir + "]";

	var text = '[dmalbum path="/' + dm_album_currdir + '"/]';

	dm_InsertIntoEditor(text);
}

function dm_UpdateAlbum(name)
{
	document.getElementById("dm_album_albumname").value = name;
}

function dm_InsertIntoEditor(text)
{
	if ( typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden() ) {
		ed.focus();
		if (tinymce.isIE)
			ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);

		ed.execCommand('mceInsertContent', false, text);
	} else
		edInsertContent(edCanvas, text);
}

function dm_StartUpload()
{
	document.getElementById("dm_album_albumname").value = g_dm_Browser.Clean(document.getElementById("dm_album_albumname").value);

	if(document.getElementById("dm_album_albumname").value == "")
	{
		alert("Sorry, you have to give an album name before uploading photos.");
		document.getElementById("dm_album_albumname").focus();
		return false;
	}

	else
	{
		if(!g_dm_UPLOAD_IN_PROGRESS)
		{
			dm_albums_swfu.setPostParams({"PHPSESSID" : g_dm_PHP_SESSION_ID, "album_name": document.getElementById("dm_album_albumname").value, "dm_uud": "<?php print(dm_user_uploaddirectory()); ?>"});
		}

		dm_albums_swfu.startUpload();
		g_dm_UPLOAD_IN_PROGRESS = true;
	}
}

function dm_FinishUpload()
{
	dm_albums_UpdateAlbumList();
	document.getElementById("dm_album_albumname").value = "";
}

function dm_ShowHideHelp()
{
	if(document.getElementById("div_dm_help").style.display == "none")
	{
		document.getElementById("div_dm_help").style.display = "";
		document.getElementById("img_dm_help_top").src 		= "<?php print($DM_RESOURCE_ROOT); ?>ui/help_colapse.gif";
		document.getElementById("img_dm_help_bottom").src 	= "<?php print($DM_RESOURCE_ROOT); ?>ui/help_colapse.gif";
	}

	else
	{
		document.getElementById("div_dm_help").style.display = "none";
		document.getElementById("img_dm_help_top").src 		= "<?php print($DM_RESOURCE_ROOT); ?>ui/help.gif";
		document.getElementById("img_dm_help_bottom").src 	= "<?php print($DM_RESOURCE_ROOT); ?>ui/help.gif";
	}
}

if (window.attachEvent) {window.attachEvent('onload', dm_InitializeSWFUpload);}
else if (window.addEventListener) {window.addEventListener('load', dm_InitializeSWFUpload, false);}
else {document.addEventListener('load', dm_InitializeSWFUpload, false);}

var g_dm_AlbumManagerLeft = null;
var g_dm_AlbumManagerTop = null;

function dm_CheckPanelPosition()
{
	try
	{
		var position = g_dm_Browser.GetPosition(document.getElementById("dmalbums_manager_id"));

		if(g_dm_AlbumManagerLeft != position[0] || g_dm_AlbumManagerTop != position[1])
		{
			dm_InitializeSWFUpload();
		}

		dm_UpdatePanelPosition();
	}
	catch(e)
	{
		return;
	}
}

function dm_UpdatePanelPosition()
{
	try
	{
		var position = g_dm_Browser.GetPosition(document.getElementById("dmalbums_manager_id"));

		g_dm_AlbumManagerLeft = position[0];
		g_dm_AlbumManagerTop = position[1];
	}
	catch(e)
	{
		return;
	}
}

setInterval("dm_CheckPanelPosition();", 5000);

//***********************************************
// DETAIL MANAGER
//***********************************************

function dm_DisplayOverlay()
{
	document.getElementById("dm_albums_darkener").style.top = "0px";
	document.getElementById("dm_albums_darkener").style.left = "0px";
	document.getElementById("dm_albums_darkener").style.width = document.body.offsetWidth + "px";
	document.getElementById("dm_albums_darkener").style.height = document.body.offsetHeight + "px";
	document.getElementById("dm_albums_darkener").style.zIndex = document.getElementById("dm_albums_album_detail_manager_container").style.zIndex - 1;
	document.getElementById("dm_albums_darkener").style.visibility = "visible";
	document.getElementById("dm_albums_darkener").style.position = "fixed";

	if(g_dm_Browser.explorer)
	{
		document.getElementById("dm_albums_darkener").style.zIndex = "900000";
		document.getElementById("dm_albums_darkener").style.filter = "alpha(opacity=75)";
	}

	else
	{
		document.getElementById("dm_albums_darkener").style.opacity = "0.75";
	}
}

function dm_DisplayDetailManager(album)
{
	dm_HideAlbumSorter();

	var offsetTop = 20;

	var width = document.body.offsetWidth;
	var height = document.body.offsetHeight;

	var left = (Math.floor((width - document.getElementById("dm_albums_album_detail_manager_container").clientWidth) / 2)) + "px";

	dm_DisplayOverlay();

	document.getElementById("dm_albums_album_detail_manager_container").style.position = "absolute";

	document.getElementById("dm_albums_album_detail_manager_container").style.top = offsetTop;
	document.getElementById("dm_albums_album_detail_manager_container").style.left = left;
	document.getElementById("dm_albums_album_detail_manager_container").style.height = height - (offsetTop * 2) + "px";
	document.getElementById("dm_albums_album_detail_manager_container").style.visibility = "visible";

	document.getElementById("dm_albums_album_detail_manager").style.height = (height - 50) - (offsetTop * 2) + "px";

	document.getElementById("dm_albums_album_detail_manager_container").style.position = "fixed";

	document.getElementById("div_dm_uploader").style.visibility = "hidden";

	document.getElementById("dm_albums_album_detail_manager").innerHTML = '<p align="center"><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/loadingAnimation.gif"/></p>';

	dm_albums_MakeAlbumDetailRequest(album);
}

function dm_HideDetailManager()
{
	document.getElementById("dm_albums_album_detail_manager_container").style.position = "absolute";
	document.getElementById("dm_albums_album_detail_manager_container").style.visibility = "hidden";
	document.getElementById("dm_albums_album_detail_manager_container").style.left = "-1000px";

	document.getElementById("dm_albums_darkener").style.position = "absolute";
	document.getElementById("dm_albums_darkener").style.visibility = "hidden";

	document.getElementById("div_dm_uploader").style.visibility = "visible";
}

function dm_albums_MakeAlbumDetailRequest(album)
{
	var timestamp = new Date();

	dm_albums_DetailAjaxRequest('<?php print($DM_RESOURCE_ROOT); ?>wp-dm-albums-ajax.php?PHPSESSID=' + g_dm_PHP_SESSION_ID + '&album_detail=' + album + "&time=" + timestamp.getTime());
}

function dm_albums_GetAlbumDetail(httpRequest)
{
	if (httpRequest.readyState == 4)
	{
        if (httpRequest.status == 200)
        {
        	document.getElementById("dm_albums_album_detail_manager").innerHTML = '<p align="center"><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/loadingAnimation.gif"/></p>';

            var xmldoc = httpRequest.responseXML;

        	var albumHTML = '<div style="padding-right: 10px">';

        	var tableHeader = '<div class="dm_photo_detail_container" width="100%"><table cellpadding="0" cellspacing="0" border="0" width="100%">';
			var tableFooter = '</table></div>';

        	if(g_dm_Browser.Exploiter)	tableHeader = '<div class="dm_photo_detail_container" width="93%"><table cellpadding="0" cellspacing="0" border="0" width="93%">';

        	var album = xmldoc.getElementsByTagName("album").item(0).firstChild.data;
			var directory = xmldoc.getElementsByTagName("location").item(0).firstChild.data;
			var title = "";

			try { title = xmldoc.getElementsByTagName("title").item(0).firstChild.data; } catch(e) {};

			albumHTML += '<p style="margin-bottom: 5px;"><a href="#" onClick="dm_ShowHideDetailHelp();"><img id="img_dm_detail_help" src="<?php print($DM_RESOURCE_ROOT); ?>ui/help.gif" border="0"/></a></p><div class="dm_photo_detail_container" id="dm_album_detail_help" style="display: none;" width="100%"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr class="dm_album_details_photo_row"><td align="left">';
			albumHTML += '<div class="dm_album_details_photo" align="left"><p align="left"><small><b>Usage:</b><br/><b>Captions</b><br/>Type captions into the text boxes labeled "Caption" below each image.</p><p align="left"><b>Photo Links</b><br/>If you would like the main (larger) photo to open a link, type the full URL you would like it open into the text boxes labeled "Photo Link" below the image.  Leaving this blank will not display any links; if you enter a link, the main photo will be clickable and clicking on this link will open it.  By default, the link will open in the main browser window, but you can also choose to have them open in a new window (or tab) by setting that option in the DM Albums Options panel.</p><p align="left"><b>Behavior</b><br/>When you click outside the box (or press Enter) the caption will update.  While a caption is updating, the text box of the caption updating will be disabled (it will be grayed out).  Please be aware that moving too quickly between fields can cause unexpected results.  The same concept applies to Album Title, Captions, and Photo Links.  To be safe, do not edit other fields while the previous text box is still grayed out.  (The Album Title will only appear when viewing your photo album in Full Screen Mode.)</small></p><p align="left"><small><b>Deleting</b><br/>To delete the entire album, click the red X next to the album name.  To delete and individual photo, click the red X next to the photo. (To add images, close this window and use the DM Albums upload tool.)</small></p></div>' +  tableFooter;

			albumHTML += tableHeader + '<tr class="dm_album_details_photo_row"><td align="left">';
			albumHTML += '<div class="dm_album_details_photo" align="left"><p align="left"><b>Album Name:</b> ' + album + '</p><p align="left"><b>Album Title:</b> <input type="text" value="' + title + '" style="width: 350px;" onChange="dm_UpdateAlbumData(this.value, \'title\', \'' + name + '\', \'' + album + '\', this);" onKeyPress="if(event.keyCode == 13) dm_UpdateAlbumData(this.value, \'title\', \'' + name + '\', \'' + album + '\', this);"/></p>';
			albumHTML += '</div>';
			albumHTML += '</td><td nowrap width="125"><a href="#" style="margin-bottom: 4px" onClick="javascript:dm_DisplayAlbumSorter(\'' + album + '\');" class="button button-highlighted">Sort Photos</a> <a style="margin-right: 5px" href="#" onClick="return dm_DeleteAlbum(\'' + directory + '\', \'' + album + '\');"><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/delete.gif" border="0"/></a></td></tr>' +  tableFooter;

			var photos = xmldoc.getElementsByTagName("photo");

        	for(var i = 0; i < photos.length; i++)
			{
    			var photo = photos[i];

				var name = photo.getElementsByTagName("name").item(0).firstChild.data;
				var caption = "";
				var link = "";

				try { caption = photo.getElementsByTagName("caption").item(0).firstChild.data; } catch(e) {};
				try { link = photo.getElementsByTagName("link").item(0).firstChild.data; } catch(e) {};

				var image = "<?php print($DM_RESOURCE_ROOT); ?>php/image.php?image=" + directory + "/" + name + "&degrees=0&scale=yes&width=550&height=550&maintain_aspect=yes&rounding=nearest";

				albumHTML += tableHeader + '<tr class="dm_album_details_photo_row">';
				albumHTML += '<td><div class="dm_album_details_photo"><p align="left"><small><b>' + name + ':</b></small></p><p><img src="' + image + '"></p>';
				albumHTML += '<p align="left"><small><b>Caption:</b></small></p><p><input type="text" name="" id="" value="' + caption + '" style="width: 100%;" onChange="dm_UpdateAlbumData(this.value, \'caption\', \'' + name + '\', \'' + album + '\', this);" onKeyPress="if(event.keyCode == 13) dm_UpdateAlbumData(this.value, \'caption\', \'' + name + '\', \'' + album + '\', this);" style="background-color: #F0F0F0;"/></p>';
				albumHTML += '<p align="left"><small><b>Photo Link:</b></small></p><p><input type="text" name="" id="" value="' + link + '" style="width: 100%;" onblur="if(!isUrl(this.value)) { alert(\'Sorry, you have to enter full URLs for this links.\'); this.focus(); }" onChange="if(isUrl(this.value)) dm_UpdateAlbumData(this.value, \'link\', \'' + name + '\', \'' + album + '\', this);" onKeyPress="if(event.keyCode == 13 && isUrl(this.value)) dm_UpdateAlbumData(this.value, \'link\', \'' + name + '\', \'' + album + '\', this);" style="background-color: #F0F0F0;"/></p>';
				albumHTML += '</div></td>';
				albumHTML += '<td align="right"><a href="#" onClick="return dm_DeletePhoto(\'' + album + '\', \'' + name + '\');"><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/delete.gif" border="0" title="Delete Photo ' + name + '" style="margin-top: 0px; margin-right: 15px;"/></a></td>';
				albumHTML += '</tr>' +  tableFooter;
			}

        	albumHTML += '</div>';

			document.getElementById("dm_albums_album_detail_manager").innerHTML = albumHTML;

        }

        else
        {
        	document.getElementById("dm_ExistingAlbums").innerHTML = '<p>A problem was encountered while getting the DM Albums Details. Please try again.</p>';
        }
    }
}

function dm_UpdateAlbumData(value, type, photo, album, obj)
{
	dm_albums_UpdateDetailAjaxRequest('<?php print($DM_RESOURCE_ROOT); ?>wp-dm-albums-ajax.php?photo=' + photo + '&album_name=' + album + '&value=' + value + '&type=' + type, obj);

	try
	{
		obj.disabled = true;
	}
	catch(e)
	{
	}
}

function dm_DeletePhoto(album, name)
{
	if(confirm("Are you sure you want to permanantly delete the photo '" + name + "'?\nThis cannot be undone.\n\n'Cancel' to stop, 'OK' to delete."))
	{
		dm_albums_DetailAjaxRequest('<?php print($DM_RESOURCE_ROOT); ?>wp-dm-albums-ajax.php?delete_photo=' + name + '&album_name=' + album + '&album_detail=' + album);
	}
}

function dm_albums_DetailAjaxRequest(url) {
    var httpRequest;

    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType) {
            httpRequest.overrideMimeType('text/xml');
            // See note below about this line
        }
    }
    else if (window.ActiveXObject) { // IE
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {}
        }
    }

    if (!httpRequest) {
        alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }

    httpRequest.onreadystatechange = function() { dm_albums_GetAlbumDetail(httpRequest); };
    httpRequest.open('GET', url, true);
    httpRequest.send('');

}

function dm_albums_UpdateDetailAjaxRequest(url, obj) {
    var httpRequest;

    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType) {
            httpRequest.overrideMimeType('text/xml');
            // See note below about this line
        }
    }
    else if (window.ActiveXObject) { // IE
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {}
        }
    }

    if (!httpRequest) {
        alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }

    httpRequest.onreadystatechange = function() { obj.disabled=false; return true; };
    httpRequest.open('GET', url, true);
    httpRequest.send('');
}


function dm_ShowHideDetailHelp()
{
	if(document.getElementById("dm_album_detail_help").style.display == "none")
	{
		document.getElementById("dm_album_detail_help").style.display = "";
		document.getElementById("img_dm_detail_help").src 	= "<?php print($DM_RESOURCE_ROOT); ?>ui/help_colapse.gif";
	}

	else
	{
		document.getElementById("dm_album_detail_help").style.display = "none";
		document.getElementById("img_dm_detail_help").src 	= "<?php print($DM_RESOURCE_ROOT); ?>ui/help.gif";
	}
}

//***********************************************
// SORT MANAGER
//***********************************************

var dm_g_OffsetTop = 0;
var dm_g_OffsetLeft = 0;

function dm_DisplayAlbumSorter(album)
{
	dm_HideDetailManager();

	var offsetTop = 20;

	var width = document.body.offsetWidth;
	var height = document.body.offsetHeight;

	var left = (Math.floor((width - document.getElementById("dm_albums_sorting_manager_container").clientWidth) / 2)) + "px";

	dm_g_OffsetTop = offsetTop;
	dm_g_OffsetLeft = left;

	dm_DisplayOverlay()

	document.getElementById("dm_albums_sorting_manager_container").style.position = "absolute";

	document.getElementById("dm_albums_sorting_manager_container").style.top = offsetTop;
	document.getElementById("dm_albums_sorting_manager_container").style.left = left;
	document.getElementById("dm_albums_sorting_manager_container").style.height = height - (offsetTop * 2) + "px";
	document.getElementById("dm_albums_sorting_manager_container").style.visibility = "visible";

	document.getElementById("dm_albums_sorting_manager").style.height = (height - 50) - (offsetTop * 2) + "px";

	document.getElementById("dm_albums_sorting_manager_container").style.position = "fixed";

	document.getElementById("div_dm_uploader").style.visibility = "hidden";

	document.getElementById("dm_albums_sorting_manager").innerHTML = '<p align="center"><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/loadingAnimation.gif"/></p>';

	dm_albums_MakeAlbumSorterRequest(album);
}

function dm_HideAlbumSorter()
{
	document.getElementById("dm_albums_sorting_manager_container").style.position = "absolute";
	document.getElementById("dm_albums_sorting_manager_container").style.visibility = "hidden";
	document.getElementById("dm_albums_sorting_manager_container").style.left = "-1000px";

	document.getElementById("dm_albums_darkener").style.position = "absolute";
	document.getElementById("dm_albums_darkener").style.visibility = "hidden";

	document.getElementById("div_dm_uploader").style.visibility = "visible";
}

function dm_albums_MakeAlbumSorterRequest(album)
{
	var timestamp = new Date();

	dm_albums_SorterAjaxRequest('<?php print($DM_RESOURCE_ROOT); ?>wp-dm-albums-ajax.php?PHPSESSID=' + g_dm_PHP_SESSION_ID + '&album_detail=' + album + "&time=" + timestamp.getTime());
}

function dm_albums_GetAlbumSorter(httpRequest)
{
	if (httpRequest.readyState == 4)
	{
        if (httpRequest.status == 200)
        {
        	document.getElementById("dm_albums_sorting_manager").innerHTML = '<p align="center"><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/loadingAnimation.gif"/></p>';

            var xmldoc = httpRequest.responseXML;

        	var albumHTML = '<div style="padding-right: 10px">';

        	var tableHeader = '<div class="dm_photo_sorting_container" width="100%"><table cellpadding="0" cellspacing="0" border="0" width="100%">';
			var tableFooter = '</table></div>';

        	if(g_dm_Browser.Exploiter)	tableHeader = '<div class="dm_albums_sorting_manager" width="93%"><table cellpadding="0" cellspacing="0" border="0" width="93%">';

        	var album = xmldoc.getElementsByTagName("album").item(0).firstChild.data;
			var directory = xmldoc.getElementsByTagName("location").item(0).firstChild.data;
			var title = "";

			try { title = xmldoc.getElementsByTagName("title").item(0).firstChild.data; } catch(e) {};

			albumHTML += '<p style="margin-bottom: 5px;"><a href="#" onClick="dm_ShowHideSortingHelp();"><img id="img_dm_sorting_help" src="<?php print($DM_RESOURCE_ROOT); ?>ui/help.gif" border="0"/></a></p><div class="dm_photo_sorting_container" id="dm_album_sorting_help" style="display: none;" width="100%"><table cellpadding="0" cellspacing="0" border="0" width="100%"><tr class="dm_album_sorting_photo_row"><td align="left">';
			albumHTML += '<div class="dm_album_sorting_photo" align="left"><p align="left"><small><b>Usage:</b><br/>Drag and drop the photos in the window below to change the order of the photos in your album.  once you are happy with the order, click the "Save Photo Order" button.  If you want to set your album back to the default order (alphabetical by photo name), click the "Reset Photo Order" button.  After clicking either of these buttons, you will see a "working" animation and when the operation is done, the album will reappear.<br/><br/>To manage captions, delete photos, or add main photo links, click the "Manage Album Details" button.</small></p></div>' +  tableFooter;

			albumHTML += tableHeader + '<tr class="dm_album_sorting_photo_row"><td align="left">';
			albumHTML += '<div class="dm_album_sorting_photo" align="left"><p align="left"><b>Album Name:</b> ' + album + '</p></div>';
			albumHTML += '</td><td align="right"><a href="#" onClick="javascript:dm_DisplayDetailManager(\'' + album + '\');" class="button button-highlighted" style="margin-right: 15px;">Manage Album Details</a> <a id="a_resetsortorder" class="button-primary" style="margin-right: 5px" href="#" onClick="return dm_ResetSortOrder(\'slideshow\', \'' + album + '\');">Rest Photo Order</a>  <a id="a_savesortorder" class="button-primary" style="margin-right: 5px" href="#" onClick="return dm_SaveSortOrder(\'slideshow\', \'' + album + '\');">Save Photo Order</a></td></tr>' +  tableFooter;

			albumHTML += '<ul id="slideshow" class="slideshow">';

			var photos = xmldoc.getElementsByTagName("photo");

        	for(var i = 0; i < photos.length; i++)
			{
    			var photo = photos[i];

				var name = photo.getElementsByTagName("name").item(0).firstChild.data;
				var caption = "";

				try { caption = photo.getElementsByTagName("caption").item(0).firstChild.data; } catch(e) {};

				var image = "<?php print($DM_RESOURCE_ROOT); ?>php/image.php?image=" + directory + "/" + name + "&degrees=0&scale=yes&width=<?php print($DM_SLIDE_SORTER_TILE_SIZE_W); ?>&height=<?php print($DM_SLIDE_SORTER_TILE_SIZE_H); ?>&maintain_aspect=yes&rounding=nearest";

				albumHTML += '<li class="slide" id="' + name + '">\n';
				albumHTML += '<div class="thumb handle"><img src="' + image + '" valign="middle"/></div>\n';
				albumHTML += '<div id="caption_' + name + '" class="caption">' + name + '</div>\n';
				albumHTML += '</li>';
			}

        	albumHTML += '</ul>';

			document.getElementById("dm_albums_sorting_manager").innerHTML = albumHTML;

			InitializeSlideSorter();

        }

        else
        {
        	document.getElementById("dm_ExistingAlbums").innerHTML = '<p>A problem was encountered while getting the DM Albums Photo Sorter. Please try again.</p>';
        }
    }
}

function dm_albums_SorterAjaxRequest(url) {
    var httpRequest;

    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType) {
            httpRequest.overrideMimeType('text/xml');
            // See note below about this line
        }
    }
    else if (window.ActiveXObject) { // IE
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {}
        }
    }

    if (!httpRequest) {
        alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }

    httpRequest.onreadystatechange = function() { dm_albums_GetAlbumSorter(httpRequest); };
    httpRequest.open('GET', url, true);
    httpRequest.send('');

}

function dm_albums_UpdateAlbumSortOrderAjaxRequest(url) {
    var httpRequest;

    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType) {
            httpRequest.overrideMimeType('text/xml');
            // See note below about this line
        }
    }
    else if (window.ActiveXObject) { // IE
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {}
        }
    }

    if (!httpRequest) {
        alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }

    httpRequest.onreadystatechange = function() { dm_albums_GetAlbumSorter(httpRequest); };
    httpRequest.open('GET', url, true);
    httpRequest.send('');
}

function dm_SaveSortOrder(albumID, album)
{
	var list = document.getElementById(albumID);
    var items = list.getElementsByTagName("li");

	var order = "";

    for (var i = 0; i < items.length; i++)
    {
        order += items[i].id;

        if(i < items.length - 1) order += ";";
    }

    document.getElementById("dm_albums_sorting_manager").innerHTML = '<p align="center"><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/loadingAnimation.gif"/></p>';

    var timestamp = new Date();

    dm_albums_UpdateAlbumSortOrderAjaxRequest('<?php print($DM_RESOURCE_ROOT); ?>wp-dm-albums-ajax.php?PHPSESSID=' + g_dm_PHP_SESSION_ID + '&album_name=' + album + '&album_order=' + order + '&album_detail=' + album + '&time=' + timestamp.getTime());
}

function dm_ResetSortOrder(albumID, album)
{
	if(!confirm("Are you sure you want to reset the photo order of your album?\nThis cannot be undone.\n\n'Cancel' to stop, 'OK' to delete."))
	{
		return false;
	}

	var list = document.getElementById(albumID);
    var items = list.getElementsByTagName("li");

	var order = "";

    for (var i = 0; i < items.length; i++)
    {
        order += items[i].id;

        if(i < items.length - 1) order += ";";
    }

    document.getElementById("dm_albums_sorting_manager").innerHTML = '<p align="center"><img src="<?php print($DM_RESOURCE_ROOT); ?>ui/loadingAnimation.gif"/></p>';

    var timestamp = new Date();

    dm_albums_UpdateAlbumSortOrderAjaxRequest('<?php print($DM_RESOURCE_ROOT); ?>wp-dm-albums-ajax.php?PHPSESSID=' + g_dm_PHP_SESSION_ID + '&album_name=' + album + '&album_reset_order=true&album_detail=' + album + '&time=' + timestamp.getTime());
}

function dm_ShowHideSortingHelp()
{
	if(document.getElementById("dm_album_sorting_help").style.display == "none")
	{
		document.getElementById("dm_album_sorting_help").style.display = "";
		document.getElementById("img_dm_sorting_help").src 	= "<?php print($DM_RESOURCE_ROOT); ?>ui/help_colapse.gif";
	}

	else
	{
		document.getElementById("dm_album_sorting_help").style.display = "none";
		document.getElementById("img_dm_sorting_help").src 	= "<?php print($DM_RESOURCE_ROOT); ?>ui/help.gif";
	}
}

// *********************
// *
// * SLIDE SORTER JS
// *
// *********************

var ESCAPE = 27
var ENTER = 13
var TAB = 9

var coordinates = ToolMan.coordinates();
var dragsort = ToolMan.dragsort();

function InitializeSlideSorter()
{
	dragsort.makeListSortable(document.getElementById("slideshow"), setHandle);
}

function setHandle(item)
{
	item.toolManDragGroup.setHandle(findHandle(item));
}

function findHandle(item)
{
	var children = item.getElementsByTagName("div");

	for (var i = 0; i < children.length; i++)
	{
		var child = children[i];

		if (child.getAttribute("class") == null) continue

		if (child.getAttribute("class").indexOf("handle") >= 0)
			return child;
	}

	return item;
}


//-->
/* ]]> */

</script>

<style>

a.dm_album_link
{
	font-size: 12px;
	display: block;
}

a.dm_detail_link
{
	display: block;
	text-decoration: none;
	font-weight: normal;
	font-size: 10px;
	padding-top: 5px;
}

.dm_container
{
	max-height: 200px;
	width: 100%;
	overflow: auto;
	margin-bottom: 15px;
}

tr.dm_existing_item
{
	margin: 10px;
}

tr.dm_existing_item:hover
{
	background-color: #EEEEEE;
}

#dm_albums_album_detail_manager_container
{
	border: 1px solid #555555;
	background-color: #222222;
	display: block;

	-moz-box-shadow: rgba(0,0,0,1) 0 4px 30px;
	-webkit-box-shadow: rgba(0,0,0,1) 0 4px 30px;
	-khtml-box-shadow: rgba(0,0,0,1) 0 4px 30px;
	box-shadow: rgba(0,0,0,1) 0 4px 30px;
}

#dm_albums_detail_manager_titlebar
{
	background-color: #222222;
	border: 0px solid #555555;
	color: #CFCFCF;
	padding: 5px;
	display: block;
	height: 20px;
}

#dm_albums_detail_manager_titlebar_title
{
	margin-top: 3px;
	float: left;
	font-weight: bold;
}

#dm_albums_detail_manager_titlebar_close
{
	padding-top: 2px;
	float: right;
}

#dm_albums_album_detail_manager
{
	margin: 10px;
	height: 90%;
	overflow: auto;
}

.dm_photo_detail_container
{
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border: 1px solid #B6B6B6;
	padding: 10px;
	background-color: #F5F5F5;
	margin-bottom: 10px !important;
}

.dm_album_details_summary
{
	margin: 10px;
	font-size: 10px;
	font-weight: bold;
	border: 0px solid #808080;
}

.dm_album_details_photo
{
	margin: 5px;
	padding-bottom: 0px;
	font-size: 10px;
	text-align: center;
}

tr.dm_album_details_photo_row
{
	background-color: #F5F5F5;
	border-bottom: 1px solid #ffffff !important;
	margin-bottom: 10px !important;
}

a.dm_detail_link
{
	display: block;
	text-decoration: none;
	font-weight: normal;

	font-size: 9px;
	margin-top: -10px;
}

#dm_albums_sorting_manager_container
{
	position: relative;
	border: 1px solid #555555;
	background-color: #222222;

	-moz-box-shadow: rgba(0,0,0,1) 0 4px 30px;
	-webkit-box-shadow: rgba(0,0,0,1) 0 4px 30px;
	-khtml-box-shadow: rgba(0,0,0,1) 0 4px 30px;
	box-shadow: rgba(0,0,0,1) 0 4px 30px;
}

#dm_albums_sorting_manager_titlebar
{
	position: relative;
	background-color: #222222;
	border: 0px solid #555555;
	color: #CFCFCF;
	padding: 5px;
	height: 20px;
}

#dm_albums_sorting_manager_titlebar_title
{
	position: relative;
	margin-top: 3px;
	float: left;
	font-weight: bold;
}

#dm_albums_sorting_manager_titlebar_close
{
	position: relative;
	padding-top: 2px;
	float: right;
}

.dm_photo_sorting_container
{
	position: relative;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border: 1px solid #B6B6B6;
	padding: 10px;
	background-color: #F5F5F5;
	margin-bottom: 10px !important;
}

#dm_albums_sorting_manager
{
	position: relative;
	margin: 10px;
	height: 90%;
	overflow: auto;
}

.dm_album_sorting_summary
{
	position: relative;
	margin: 10px;
	font-size: 10px;
	font-weight: bold;
	border: 0px solid #808080;
}

.dm_album_sorting_photo
{
	position: relative;
	margin: 5px;
	padding-bottom: 0px;
	font-size: 10px;
	text-align: center;
}

tr.dm_album_sorting_photo_row
{
	position: relative;
	background-color: #F5F5F5;
	border-bottom: 1px solid #ffffff !important;
	margin-bottom: 10px !important;
}

a.dm_sorting_link
{
	position: relative;
	text-decoration: none;
	font-weight: normal;

	font-size: 9px;
	margin-top: -10px;
}

/****************/
/* SLIDE SORTER */
/****************/

.slideshow {
	position: relative;
	list-style-type: none;
	margin: 0px;
	padding: 0px;
}

.slide {
	position: relative;
	float: left;
	width: <?php print($DM_SLIDE_SORTER_TILE_SIZE_W + 2); ?>px;
	margin-bottom: 10px;
	margin-right: 12px;
}

.slide div.thumb
{
	position: relative;
	text-align: center;
	background: #fff;
	width: <?php print($DM_SLIDE_SORTER_TILE_SIZE_W); ?>px;
	height: <?php print($DM_SLIDE_SORTER_TILE_SIZE_H); ?>px;
	border: 1px solid #ccc;
	font-size: 5px;
	overflow: hidden;
	background-color: #eee;
}

.slide .caption {
	position: relative;
	padding: 2px 2px;
	margin: 2px 0px;
	border-width: 1px;
	border-style: solid;
	border-color: #ccc;
	background-color: #eee;
	height: 14px;
}
.caption:hover {
	cursor:default;
}

.caption {
	position: relative;
	overflow: hidden;
	padding-bottom: 2px;
	font-size: 10px;
}

.caption {
	position: relative;
	text-align: center;
}

.handle {
	position: relative;
	cursor: move;
}

</style>
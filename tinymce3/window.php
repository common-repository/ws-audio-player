<?php

$wpconfig = realpath("../../../../wp-config.php");

if (!file_exists($wpconfig))  {
	echo "Could not found wp-config.php. Error in path :\n\n".$wpconfig ;
	die;
}// stop when wp-config is not there

require_once($wpconfig);
require_once(ABSPATH.'/wp-admin/admin.php');

// check for rights
if(!current_user_can('edit_posts')) die;

global $wpdb;

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>WS Audio Player</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo WSAP_URLPATH ?>tinymce3/tinymce.js"></script>
	<base target="_self" />
</head>
<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';document.getElementById('MusicURL').focus();" style="display: none">
<!-- <form onsubmit="insertLink();return false;" action="#"> -->
	<form name="wsap" action="#">
	<div class="tabs">
		<ul>
			<li id="product_tab" class="current"><span><a href="javascript:mcTabs.displayTab('product_tab','wsap_panel');" onmousedown="return false;"><?php _e("设置", 'wsap'); ?></a></span></li>
		</ul>
	</div>

	<div class="panel_wrapper">
		<!-- quickshop panel -->
		<div id="wsap_panel" class="panel current">
		<table border="0" cellpadding="3" cellspacing="0">
         <tr>
            <td nowrap="nowrap"><label for="MusicURL"><?php _e("音乐地址", 'wsap'); ?></label></td>
            <td><input id="MusicURL" name="MusicURL" style="width: 200px" value="http://"/></td>
         </tr>
         <tr>
            <td nowrap="nowrap"><label for="MusicTitle"><?php _e("音乐标题", 'wsap'); ?></label></td>
            <td><input id="MusicTitle" name="MusicTitle" style="width: 200px"  /></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><label for="MusicDownload"><?php _e("下载功能", 'wsap'); ?></label></td>
            <td><input id="MusicDownload" type="checkbox" name="MusicDownload" /><?php _e("是否显示下载按钮", 'wsap'); ?></td>
          </tr>
        </table>
        <p>
            <?php
                _e('温馨提示：音乐文件只支持MP3格式。<br />如果你有更好的建议和想法，你可以 <a href="mailto:icyleaf.cn@gmail.com">写信给我 </a>或者在 <a href="http://icyleaf.com" target="_blank">我的博客</a> 上面留言:)</p>','wsap');
            ?>
		</div>
		<!-- quickshop panel -->
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="<?php _e("取消", 'wsap'); ?>" onclick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="<?php _e("插入", 'wsap'); ?>" onclick="insertWSAPLink();" />
		</div>
	</div>
</form>
</body>
</html>

<?php
/*
Author: WS Audio Player
Author: URI: http://icyleaf.com
Description: 附带下载功能的在线Flash播放器.
*/
//'wp-content/plugins/ws-audio-player/lanugages'
load_plugin_textdomain('wsap', FALSE, dirname(plugin_basename(__FILE__)).'/lanugages');

// Form Action URI
$location = get_option('siteurl') . '/wp-admin/admin.php?page=ws-audio-player/options-wsaudioplayer.php';
$img_location = get_option('siteurl') . '/wp-content/plugins/ws-audio-player/img/';

//Default  CSS Setting
add_option('wsap_css_setting', __('screen_white','wsap'));
add_option('wsap_css_custom_content', '', 'wsap');

if ('process' == $_POST['stage']) {
	update_option('wsap_css_setting', $_POST['wsap_css_setting'] );
	update_option('wsap_css_custom_content', $_POST['wsap_css_custom_content'] );
}

$wsap_css_setting = get_option('wsap_css_setting');
$wsap_css_custom_content = get_option('wsap_css_custom_content');

//Default Values
$cssfile=GetPluginPath() . "css/screen.css";
$Radio1 = "Checked";

//GetPluginPath
function GetPluginPath() {
	$path = dirname(__FILE__);
	return trailingslashit(str_replace("\\","/",$path));
}

//Radio Select
if ( $wsap_css_setting!="") {
	switch ($wsap_css_setting){
		case 'wsap_css_white':$Radio1 = "Checked";$wsap_css_custom_content = "";break;
		case 'wsap_css_blue':$Radio2 = "Checked";$wsap_css_custom_content = "";break;
		case 'wsap_css_classic':$Radio3 = "Checked";$wsap_css_custom_content = "";break;
		case 'wsap_css_custom':$Radio4 =  "Checked";break;
		default:$Radio1 = "Checked";
	}
}

//Write Cunstom CSS style to screen.css
if ( isset($_POST['Submit']) ) {
if ( $wsap_css_custom_content!="" ){
	if (is_writeable($cssfile)) {
		$f = fopen($cssfile, 'w');
		fwrite($f, $wsap_css_custom_content);
		fclose($f);
	}else{_e('CSS文件不能写入', 'wsap');}
}
}
?>

<div class="wrap">
<form name="form1" method="post" action="<?php echo $location ?>&updated=true">
<input type="hidden" name="stage" value="process" />
<h3><?php _e('使用说明', 'wsap') ?></h3>
<span style="color:red;font:18px bolid"><?php _e('<b>提示</b>: 如果你的WordPrss版本在2.5+，则可以在富文本模式和代码模式下直接点击按钮插入！如果你想DIY，则可以参考下面的代码', 'wsap') ?></span>
<table width="100%" cellspacing="2" cellpadding="5" class="editform">
	<tr valign="top">
		<th scope="row" nowrap> <?php _e('代码格式', 'wsap') ?>：</th>
		<td><?php _e('发布日志的时候，在代码状态输入：<b>[audio=音乐的地址</b><i>[,音乐名称,download]</i><b>]</b><br >其中音乐名称和download（是否可下载）为可选择选项。', 'wsap') ?></td>
	</tr>
	<tr valign="top" >
		<th scope="row" nowrap><?php _e('举例说明', 'wsap') ?>：</th>
		<td><?php _e('插入一首名为“<b>爱转角</b>”，音乐的地址为<b>http://icyleaf.com/music.mp3</b>', 'wsap') ?></td>
	</tr>
      	<tr valign="top">
			<th scope="row" nowrap><?php _e('样式1', 'wsap') ?>：</th>
			<td><?php _e('只显示播放器<br /><b>[audio=http://icyleaf.com/music.mp3]</b>', 'wsap') ?></td>
      	</tr>
      	<tr valign="top">
			<th scope="row" nowrap><?php _e('样式2', 'wsap') ?>：</th>
			<td><?php _e('只显示播放器以及音乐名称<br /><b>[audio=http://icyleaf.com/music.mp3,爱转角]</b>', 'wsap') ?></td>
      	</tr>
	<tr valign="top">
			<th scope="row" nowrap><?php _e('样式3', 'wsap') ?>：</th>
			<td><?php _e('只显示播放器以及下载按钮（注意，中间有两个逗号）<br /><b>[audio=http://icyleaf.com/music.mp3,,download]</b>', 'wsap') ?></td>
      	</tr>
	<tr valign="top">
			<th scope="row" nowrap><?php _e('样式4', 'wsap') ?>：</th>
			<td><?php _e('全部显示：播放器，音乐名称，下载按钮<br /><b>[audio=http://icyleaf.com/music.mp3,爱转角,download]</b>', 'wsap') ?></td>
      	</tr>
	<tr valign="top">
			<th scope="row" nowrap><?php _e('注意事项', 'wsap') ?>：</th>
			<td><?php _e('1.除了音乐名称外，其他符号均为英文半角状态。<br />2.如果只准备显示播放器以及下载按钮，不要忘记中间为两个逗号。', 'wsap') ?></td>
      	</tr>
</table>

<h3><?php _e('外观设置', 'wsap') ?></h3>
<?php
if (! is_writable(GetPluginPath() . "css/screen.css")) {
echo  "<div class='updated'><code>" . GetPluginPath() . "css/screen.css</code>不可写。<br />如果选择自定义CSS的话，必须使得screen.css文件的属性为可写（777）。</div>";
}
?>
<table width="500" cellspacing="2" cellpadding="5" class="editform">
	<tr valign="top">
		<th scope="row" nowrap><?php _e('外观选择', 'wsap') ?>；</th>
		<td>
			<ul>
				<li>
					<input type="radio" value="wsap_css_white" name="wsap_css_setting" value="" <?php echo $Radio1; ?> onclick="return HideTextArea()"/>
					<?php _e('White(默认)', 'wsap') ?><br><img src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/ws-audio-player/img/AudioPlayer_white.jpg" />
				</li>
				<li>
					<input type="radio" value="wsap_css_blue" name="wsap_css_setting" value="" <?php echo $Radio2; ?> onclick="return HideTextArea()"/>
					<?php _e('Blue', 'wsap') ?><br><img src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/ws-audio-player/img/AudioPlayer_blue.jpg" />
				</li>
				<li>
					<input type="radio" value="wsap_css_classic" name="wsap_css_setting" value="" <?php echo $Radio3; ?> onclick="return HideTextArea()" />
					<?php _e('白色经典', 'wsap') ?><br><img src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/ws-audio-player/img/AudioPlayer_classic.jpg" />
				</li>
				<li>
					<input type="radio" value="wsap_css_custom" name="wsap_css_setting" value="" <?php echo $Radio4; ?> onclick="return ShowTextArea()" />
					<?php _e('自定义CSS', 'wsap') ?>
					<div id="wsap_css_textarea" style="display:none;">
					<?php
					if (file_exists($cssfile) && is_readable ($cssfile)) {
						$content = fopen($cssfile, "r");
					?>

					<textarea cols="60" rows="12" name="wsap_css_custom_content"><?php
					while (!feof($content)) {
					$wsap_css_custom_content = fgets($content);
					echo $wsap_css_custom_content;
					}
					?></textarea>
					<?php
					fclose($content);
					}else{_e('CSS文件不能写入！', 'wsap');}
					?>
					</div>
				</li>
			</ul>
		</td>
	</tr>
	<tr valign="top">
			<th scope="row"><?php _e('设置提示', 'wsap') ?>：</th>
			<td><?php _e('1.外观除了Flash播放器本身以外（白色），均使用CSS控制其效果。<br />2.如果你要自定义CSS样式，请先screen.css文件的属性设置为777。', 'wsap') ?></td>
    </tr>
</table>
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('保存设置', 'wsap') ?> &raquo;" />
</p>
</form>
<h3 style="pading-bottom:10px;"><?php _e('捐赠本插件', 'wsap') ?></h3>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="">
<img alt="" border="0" src="https://www.paypal.com/zh_XC/i/scr/pixel.gif" width="1" height="1">
</form>
</div>

<script language="javascript">
function ShowTextArea(){
	document.getElementById("wsap_css_textarea").style.display = "block";
}
function HideTextArea(){
	document.getElementById("wsap_css_textarea").style.display = "none";
}
</script>

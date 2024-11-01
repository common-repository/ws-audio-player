<?php
/*
Plugin Name: WS Audio Player
Plugin URI: http://icyleaf.com/projects/ws-audio-player/
Description: 可在日志插入且附带下载功能的在线Flash播放器。(Add Audio Player with Download function to your blog. After active,you can goto Options to set Style of Audio Player).
Version: 1.1.8
Author: icyleaf
Author URI: http://icyleaf.com
*/

// Global variables
define('IS_WP25', version_compare($wp_version, '2.4', '>=') );
define('IS_WP28', version_compare($wp_version, '2.8.1', '>=') );
load_plugin_textdomain('wsap', FALSE, dirname(plugin_basename(__FILE__)).'/lanugages');
// define URL
$myabspath = str_replace("\\","/",ABSPATH);  // required for Windows & XAMPP
define('WINABSPATH', $myabspath);
define('WSAPFOLDER', dirname(plugin_basename(__FILE__)));
define('WSAP_ABSPATH', $myabspath.'wp-content/plugins/' . WSAPFOLDER .'/');
define('WSAP_URLPATH', get_option('siteurl').'/wp-content/plugins/' . WSAPFOLDER.'/');

$wsap_playerURL = get_settings('siteurl') . '/wp-content/plugins/ws-audio-player/audioplayer.swf';
$wasp_root	= get_settings('siteurl') . '/wp-content/plugins/'.dirname(plugin_basename(__FILE__));

function wsap_add_options_page()
{
	add_options_page('WS AudioPlayer Options', 'WS AudioPlayer', 'manage_options', 'ws-audio-player/options-wsaudioplayer.php');
}

if (IS_WP28) 
{
	add_action('admin_menu', 'wsap_add_options_page');
}
else 
{
	add_action('admin_head', 'wsap_add_options_page');
}

// Declare instances global variable
$wsap_instances = array();

/***********************************************************************
*	Filter function (inserts player instances according to behaviour option)
************************************************************************/
function wsap_insert_player($content = '') 
{
	global $wsap_behaviour, $wsap_instances;

	// Reset instance array
	$wsap_instances = array();
	$content = preg_replace_callback("/\[audio=(([^]]+))]/i", 'wsap_player', $content);
	
	return $content;
}
add_filter('the_content', 'wsap_insert_player');


/***********************************************************************
*	Callback function for preg_replace_callback
************************************************************************/
function wsap_player($matches)
{
	global $wasp_root, $wsap_playerURL, $wsap_instances, $wsap_playerID;

	// Get next player ID
	$wsap_playerID++;
	// Build FlashVars string (url encode everything)
	$flashVars = 'audio' . $wsap_playerID;

	// Split options
	$data = preg_split("/[\|]/", $matches[2]);
	$files = array();

	foreach(explode(',', $data[0]) as $afile) 
	{
		array_push( $files, $afile );
	}
	
	$file = implode(',', $files);
	$source = split(',', $file);

	//feed settings
	if(is_feed()) 
	{
		$output = '<br /><img src="'.$wasp_root.'/img/music.gif" alt="music" />';
		$output .= 'Author insert a music with <a href="http://icyleaf.com/projects/ws-audio-player/">WS Audio Player</a>.';
		if( ! empty($source[1]))
		{
			$output .= '<br />Download (<a href="' . $source[0] . '" title="Download ' . $source[1] . '"/>' . $source[1] . '</a>).';
		}
		else
		{
			$output .= '<br />(<a href="' . $source[0] . '" />Download</a>) this music.';
		}
	}
	else
	{
		$output = '<div class="wsaudioplayer">
				<div class="wsflashplayer">';
		$output .= '<embed width="100%" height="26" flashvars="url=' . $source[0] . '" wmode="transparent" quality="high" class="audio" name="' . $flashVars . '" src="' . $wsap_playerURL . '" type="application/x-shockwave-flash"/></embed></div>';
	
		if($source[2]=="download") $output .= '<a href="' . $source[0] . '" title="Download ' . $source[1] .'" class="wsdownload">Download</a>';
		if($source[1]!="") $output .= '<span class="wsmusicdesc">'. $source[1] . '</span>';
		$output .='</div></p>';
	}
	
	return $output;
}

/***********************************************************************
*	Insert Setting files function
************************************************************************/
function wsap_wp_head()
{
	global $wsap_playerID;

	$wsap_css = get_option('wsap_css_setting');
	switch($wsap_css)
	{
		case 'wsap_css_white':
			$wsap_css = 'screen_white.css';
			break;
		case 'wsap_css_blue':
			$wsap_css = 'screen_blue.css';
			break;
		case 'wsap_css_classic':
			$wsap_css = 'screen_classic.css';
			break;
		case 'wsap_css_custom':
			$wsap_css = 'screen.css';
			break;
		default:
			$wsap_css = 'screen_white.css';
	}

	echo "\n\t<!--Begin[CSS for WS Audio Player]-->\n\t";
	echo '<link type="text/css" rel="stylesheet" href="' . WSAP_URLPATH . 'css/' . $wsap_css  .'" />';
	echo "\n\t<!--End[CSS for WS Audio Player]-->\n\n";
}
add_action('wp_head', 'wsap_wp_head');


/***********************************************************************
*	Toolbar Button of HTML Mode Functions
************************************************************************/
if (strpos($_SERVER['REQUEST_URI'], 'post.php') || strpos($_SERVER['REQUEST_URI'], 'post-new.php') ||
	strpos($_SERVER['REQUEST_URI'], 'page-new.php') || strpos($_SERVER['REQUEST_URI'], 'page.php')) 
{
	add_action('admin_footer', 'wsap_AddQuickTag');

	function wsap_AddQuickTag()
	{
		echo '<script type="text/javascript">
				<!--
				var wsapToolbar = document.getElementById("ed_toolbar");
				if(wsapToolbar){
					var wsapNr = edButtons.length;';

		echo "edButtons[edButtons.length] = new edButton('ed_wsap','','','','');";
		echo 'var wsapBut = wsapToolbar.lastChild;
					while (wsapBut.nodeType != 1){
						wsapBut = wsapBut.previousSibling;
					}

					wsapBut = wsapBut.cloneNode(true);
					wsapToolbar.appendChild(wsapBut);
					//toolbar.appendChild(wsapBut);
					wsapBut.value = \'MP3\';
					wsapBut.onclick = edInsertwsap;
					wsapBut.title = "Input Music with WS Audio Player plugins";
					wsapBut.id = "ed_wsp";
				}

				function edInsertwsap() {
					if(!edCheckOpenTags(wsapNr)){';

					echo "var U = prompt('";
					_e('请输入MP3地址[MP3 URI]','wsap');
					echo "' , 'http://');
							var W = prompt('";
					_e('是否显示歌曲名,不显示请留空[Will you show Music Name?]','wsap');
					echo "' , '');
							var H = prompt('";
					_e('是否可以下载,不可以请留空,默认为可以下载[Can it download?]','wsap');
					echo "' , 'download');
							var theTag = '[audio=' + U + ',' + W + ',' + H + ']';";
					echo	'edButtons[wsapNr].tagStart  = theTag;
						edInsertTag(edCanvas, wsapNr);
					} else {
						edInsertTag(edCanvas, wsapNr);
					}
				}

				//-->
				</script>';
	}
}

/***********************************************************************
*	Toolbar Button of Vistul Mode Functions
************************************************************************/
if (IS_WP25)
{
	function wsap_addbuttons()
	{
		// Don't bother doing this stuff if the current user lacks permissions
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages'))
		{
			return;
		} 
		
		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true')
		{
			// add the button for wp25 in a new way
			add_filter('mce_external_plugins', 'add_wsap_tinymce_plugin', 5);
			add_filter('mce_buttons', 'register_wsap_button', 5);
		}
	}

	// used to insert button in wordpress 2.5x editor
	function register_wsap_button($buttons)
	{
		array_push($buttons, 'separator', 'WSAPMP3');
		return $buttons;
	}

	// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
	function add_wsap_tinymce_plugin($plugin_array) 
	{
		$plugin_array['WSAPMP3'] = WSAP_URLPATH.'tinymce3/editor_plugin.js';
		return $plugin_array;
	}

	function wsap_change_tinymce_version($version) 
	{
		return ++$version;
	}

	// Modify the version when tinyMCE plugins are changed.
	add_filter('tiny_mce_version', 'wsap_change_tinymce_version');
	// init process for button control
	add_action('init', 'wsap_addbuttons');
}
else
{
	add_action('admin_notices', 'wsap_version_warning');
}
add_action('admin_notices', 'wsap_update_warning');

/***********************************************************************
*	Display Warning Message Function
************************************************************************/
function wsap_update_warning()
{
	if ( ! is_dir(WSAP_ABSPATH."tinymce3"))
	{
		echo "<div id='warning' class='updated fade'><p><strong>".__('升级警告！','wsap')."</strong> ".
		sprintf(__('由WS Audio Player升级不当的原因，造成某些文件和文件夹丢失，请<a href="%1$s">点击这里</a>重新下载最新版本并重新安装本插件。','wsap'),
		"http://downloads.wordpress.org/plugin/ws-audio-player.zip")."</p></div>";
	 }
}

function wsap_version_warning()
{
	echo '<div id="warning" class="updated fade"><p><strong>'. __('版本警告！', 'wsap'). '</strong> '.
		__('你当前使用的Wordpress不是2.5版本，请更新Wordpress版本至2.5+以上在使用WS Audio Player插件。谢谢你的支持！', 'wsap').'</p></div>';
}
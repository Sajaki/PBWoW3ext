<?php
/**
 *
 * @package PBWoW Extension
 * English translation by PayBas
 *
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	// Extension modules
	'ACP_PBWOWEXT_CATEGORY'		=> 'PBWoW 3',
	'ACP_PBWOWEXT_CONFIG'			=> 'Configuration',

	// Common
	'PBWOW_ACTIVE'				=> 'active',
	'PBWOW_INACTIVE'			=> 'inactive',
	'PBWOW_DETECTED'			=> 'detected',
	'PBWOW_NOT_DETECTED'		=> 'not detected',
	'PBWOW_OBSOLETE'			=> 'no longer used',
	'PBWOW_FLUSH'				=> 'Flush',
	'PBWOW_FATAL'				=> 'Fatal error! This really should never happen.',

	'LOG_PBWOW_CONFIG'			=> '<strong>Altered PBWoW settings</strong><br />&raquo; %s',

	// OVERVIEW //
	'PBWOW_OVERVIEW_TITLE'				=> 'PBWoW Extension Overview',
	'PBWOW_OVERVIEW_TITLE_EXPLAIN'		=> 'Thank you for choosing PBWoW, hope you like it.',
	'ACP_PBWOW_INDEX_SETTINGS'			=> 'General information',

	'PBWOW_DB_CHECK'					=> 'PBWoW Database Check',
	'PBWOW_DB_GOOD'						=> 'PBWoW configuration table found (%s)',
	'PBWOW_DB_BAD'						=> 'No PBWoW configuration table found. Make sure that the table (%s) exists in your phpBB database.',
	'PBWOW_DB_BAD_EXPLAIN'				=> 'Try to disable and re-enable the PBWoW 3 extension. If that does not work, disable the extension and delete the data. Then try enabling it again.',

	'PBWOW_VERSION_CHECK'				=> 'PBWoW Version Check',
	'PBWOW_LATEST_VERSION'				=> 'Latest version',
	'PBWOW_EXT_VERSION'					=> 'Extension version',
	'PBWOW_STYLE_VERSION'				=> 'Style version',
	'PBWOW_LATEST_STYLE_VERSION'		=> 'Latest Style version',
	'PBWOW_VERSION_ERROR'				=> 'Unable to check latest version!',
	'PBWOW_CHECK_UPDATE'				=> 'Check <a href="http://www.avathar.be/bbdkp/index.php">avathar.be</a> to see if there are updates available.',

	'PBWOW_DONATE_URL'                  => 'http://www.avathar.be/bbdkp/app.php/page/donate',
	'PAYPAL_IMAGE_URL'                  => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                        => 'Donate using PayPal',
	'PBWOW_DONATE'						=> 'Donate to PBWoW',
	'PBWOW_DONATE_SHORT'				=> 'Make a donation to PBWoW',
	'PBWOW_DONATE_EXPLAIN'				=> 'PBWoW is 100% free. It is a hobby project that I am spending my time and money on, just for the fun of it. If you enjoy using PBWoW, please consider making a donation. I would really appreciate it. No strings attached.',

	// CONFIG //
	'PBWOW_CONFIG_TITLE'				=> 'PBWoW Configuration',
	'PBWOW_CONFIG_TITLE_EXPLAIN'		=> 'Here you can choose some options to customize your PBWoW installation.',

	'PBWOW_LOGO'						=> 'Custom Logo',
	'PBWOW_LOGO_ENABLE'					=> 'Enable your own custom logo image',
	'PBWOW_LOGO_ENABLE_EXPLAIN'			=> 'Using this will enable your own custom logo for all installed PBWoW styles (except the PBWoW master style).',
	'PBWOW_LOGO_SRC'					=> 'Image source path',
	'PBWOW_LOGO_SRC_EXPLAIN'			=> 'Image path under your phpBB root directory, e.g. <samp>images/logo.png</samp>.<br />I strongly advise you to use a PNG image with a transparent background.',
	'PBWOW_LOGO_SIZE'					=> 'Logo dimensions',
	'PBWOW_LOGO_SIZE_EXPLAIN'			=> 'Exact dimensions of your logo image (Width x Height in pixels).<br />Images of more than 350 x 200 are not advised (due to responsive layout).',
	'PBWOW_LOGO_MARGINS'				=> 'Logo margins',
	'PBWOW_LOGO_MARGINS_EXPLAIN'		=> 'Set the CSS margins of your logo. This will give more control over the positioning of your image. Use valid CSS markup, e.g. <samp>10px 5px 25px 0</samp>.',

	'PBWOW_TOPBAR'						=> 'Top Header-Bar',
	'PBWOW_TOPBAR_ENABLE'				=> 'Enable the top header-bar',
	'PBWOW_TOPBAR_ENABLE_EXPLAIN'		=> 'By enabling this option, a 40px high customizable bar will be displayed at the top of each page.',
	'PBWOW_TOPBAR_CODE'					=> 'Top header-bar code',
	'PBWOW_TOPBAR_CODE_EXPLAIN'			=> 'Enter your code here. Use &lt;span&gt; or &lt;a class="cell"&gt; elements to separate blocks with borders. To use icons, either use &lt;img&gt; blocks or define special CSS classes inside your custom.css stylesheet (better).',
	'PBWOW_TOPBAR_FIXED'				=> 'Fixed to top',
	'PBWOW_TOPBAR_FIXED_EXPLAIN'		=> 'Fixing the top header-bar to the top of the screen will keep it visible and locked in place, even when scrolling.<br />This does not apply to mobile devices. It will revert back to default (scrolling) mode when viewed on small screens.',

	'PBWOW_HEADERLINKS'					=> 'Header Box Custom Links',
	'PBWOW_HEADERLINKS_ENABLE'			=> 'Enable custom links in the header box',
	'PBWOW_HEADERLINKS_ENABLE_EXPLAIN'	=> 'By enabling this option, the HTML code entered below will be displayed inside the box at the top-right of the screen (in-line before the FAQ link). This is useful for portal and DKP links (some of which will be detected automatically).',
	'PBWOW_HEADERLINKS_CODE'			=> 'Custom header links code',
	'PBWOW_HEADERLINKS_CODE_EXPLAIN'	=> 'Enter your custom links here. These should be wrapped in &lt;li&gt; elements. To use icons, please define CSS classes inside your custom.css stylesheet.',

	'PBWOW_VIDEOBG'						=> '(Video) Background Settings',
	'PBWOW_VIDEOBG_ENABLE'				=> 'Enable animated video backgrounds',
	'PBWOW_VIDEOBG_ENABLE_EXPLAIN'		=> 'Some PBWoW styles support special animated video backgrounds (not all). You can enable these for cool effect, or disable them to save bandwidth (or if you are having problems).',
	'PBWOW_VIDEOBG_ALLPAGES'			=> 'Display video backgrounds on all pages?',
	'PBWOW_VIDEOBG_ALLPAGES_EXPLAIN'	=> 'By default, PBWoW only loads the video backgrounds (if available) on <u>index.php</u> pages. You can enable them for all pages, but this may affect the browsing speed of your visitors (but in general not your server bandwidth, because they are cached locally). [only applies if video is enabled]',

	'PBWOW_FIXEDBG'						=> 'Fixed background position',
	'PBWOW_FIXEDBG_EXPLAIN'				=> 'Fixing the background position (including video) will prevent it from scrolling along with the rest of the content. Keep in mind that some lower resolution devices will have no option to see the entire background image.',

	'PBWOW_ADS_INDEX'					=> 'Index Advertisement Block for Recent Topics',
	'PBWOW_ADS_INDEX_ENABLE'			=> 'Enable index advertisement',
	'PBWOW_ADS_INDEX_ENABLE_EXPLAIN'	=> 'Enabling this ad will generate a narrow advertisement block on the forum index page (requires Recent Topics extension).',
	'PBWOW_ADS_INDEX_CODE'				=> 'Index advertisement code',
	'PBWOW_ADS_INDEX_CODE_EXPLAIN'		=> 'This block is suitable for advertisements with a <u>width</u> of: <b>300px</b>.<br />If you want to use/change custom CSS styling, please add it to <samp>ext/paybas/pbwowext/styles/pbwow3/theme/pbwowext.css</samp>',
));

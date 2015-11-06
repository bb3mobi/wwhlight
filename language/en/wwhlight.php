<?php
/**
*
* @package - wwhlight
* @copyright (c) alg
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
	 // for the normal sites
	//'WHO_WAS_HERE'					=> 'Кто сегодня был на конференции',
	'WWHLIGHT_TITLE'		=> 'Who was here :',
	'WWHLIGHT_LATEST'			=> 'last at: %s',

	'WWHLIGHT_TOTAL'			=> array(
		0		=> 'In total there were <strong>0</strong> users online :: ',
		1		=> 'In total there was <strong>%d</strong> user online :: ',
		2		=> 'In total there were <strong>%d</strong> users online :: ',
	),
	'WWHLIGHT_REG_USERS'		=> array(
		0		=> '0 registered',
		1		=> '%d registered',
		2		=> '%d registered',
	),
	'WWHLIGHT_HIDDEN_USERS'			=> array(
		0		=> '0 hidden',
		1		=> '%d hidden',
		2		=> '%d hidden',
	),

	'WWHLIGHT_EXPLANE'				=> 'based on users active today, updated once per hour',
	'WWHLIGHT_RECORD'			=> 'Most users ever online was: <strong>%1$s</strong>, between: %2$s',
));

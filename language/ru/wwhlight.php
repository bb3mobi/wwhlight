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
	'WWHLIGHT_TITLE'		=> 'Спасибо заглянувшим сегодня в "Местечко" :',
	'WWHLIGHT_LATEST'			=> 'последнее посещение: %s',

	'WWHLIGHT_TOTAL'			=> array(
		0		=> 'Сегодня здесь было <strong>0</strong> посетителей: ',
		1		=> 'Сегодня здесь был <strong>%d</strong> посетитель:: ',
		2		=> 'Сегодня здесь было <strong>%d</strong> посетителей:: ',
	),
	'WWHLIGHT_REG_USERS'		=> array(
		0		=> '0 зарегистрированных',
		1		=> '%d зарегистрированный',
		2		=> '%d зарегистрированных',
	),
	'WWHLIGHT_HIDDEN_USERS'			=> array(
		0		=> ' и 0 скрытых',
		1		=> ' и %d скрытый',
		2		=> ' и %d скрытых',
	),

	'WWHLIGHT_EXPLANE'				=> 'основано на активности посетителей за последние сутки, обновляется раз в час',
	'WWHLIGHT_RECORD'			=> 'Больше всего посетителей: <strong>%1$s</strong>, здесь было: %2$s',
));

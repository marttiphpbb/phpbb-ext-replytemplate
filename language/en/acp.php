<?php

/**
* phpBB Extension - marttiphpbb Reply Template
* @copyright (c) 2015 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [

	'ACP_MARTTIPHPBB_REPLYTEMPLATE'			=> 'Reply template',
	'ACP_MARTTIPHPBB_REPLYTEMPLATE_EXPLAIN'	=> 'When replying in a topic, the text editor will be pre-filled with this "Reply Template". Leave blank when you don’t wish to use this. This functionality comes from the "Reply Template" extension.',
]);

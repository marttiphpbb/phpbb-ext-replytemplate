<?php
/**
* phpBB Extension - marttiphpbb Reply Template
* @copyright (c) 2015 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\replytemplate\migrations;

use marttiphpbb\replytemplate\service\store;

class v_0_1_0 extends \phpbb\db\migration\migration
{
	static public function depends_on():array
	{
		return [
			'\phpbb\db\migration\data\v320\v320',
		];
	}

	public function update_data():array
	{
		return [
			['config_text.add', [store::KEY, serialize([])]],
		];
	}
}

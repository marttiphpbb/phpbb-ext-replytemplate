<?php
/**
* phpBB Extension - marttiphpbb Reply Template
* @copyright (c) 2015 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\replytemplate\event;

use phpbb\event\data as event;
use phpbb\db\driver\factory as db;
use phpbb\request\request;
use phpbb\language\language;
use marttiphpbb\replytemplate\service\store;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class acp_listener implements EventSubscriberInterface
{
	protected $db;
	protected $request;
	protected $language;
	protected $store;
	protected $forums_table;

	public function __construct(
			db $db,
			request $request,
			language $language,
			store $store,
			string $forums_table
	)
	{
		$this->db = $db;
		$this->request = $request;
		$this->language = $language;
		$this->store = $store;
		$this->forums_table = $forums_table;
	}

	static public function getSubscribedEvents():array
	{
		return [
			'core.acp_manage_forums_initialise_data'	=> 'core_acp_manage_forums_initialise_data',
			'core.acp_manage_forums_update_data_after'	=> 'core_acp_manage_forums_update_data_after',
			'core.acp_manage_forums_display_form'		=> 'core_acp_manage_forums_display_form',
		];
	}

	public function core_acp_manage_forums_initialise_data(event $event):void
	{
		/**
			because there's no php event where a form is deleted,
			we do here cleanup of Reply Templates of deleted forums.
			This functions runs when adding or editing a forum in ACP.
		*/

		$keep_forum_ids = [];

		$sql = 'select forum_id from ' . $this->forums_table;
		$result = $this->db->sql_query($sql);
		while ($forum_id = $this->db->sql_fetchfield('forum_id'))
		{
			$keep_forum_ids[] = $forum_id;
		}
		$this->db->sql_freeresult($result);

		$this->store->delete_all_templates_but($keep_forum_ids);
	}

	public function core_acp_manage_forums_update_data_after(event $event):void
	{
		$forum_data = $event['forum_data'];
		$forum_id = $forum_data['forum_id'];

		$reply_template = utf8_normalize_nfc($this->request->variable('forum_marttiphpbb_replytemplate', '', true));
		$this->store->set_template($forum_id, $reply_template);
	}

	public function core_acp_manage_forums_display_form(event $event):void
	{
		$action = $event['action'];
		$forum_id = $event['forum_id'];
		$template_data = $event['template_data'];

		$reply_template = $action === 'add' ? '' : $this->store->get_template($forum_id);

		$template_data['FORUM_MARTTIPHPBB_REPLYTEMPLATE'] = $reply_template;

		$event['template_data'] = $template_data;

		$this->language->add_lang('acp', 'marttiphpbb/replytemplate');
	}
}

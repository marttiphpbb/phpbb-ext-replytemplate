<?php
/**
* phpBB Extension - marttiphpbb Reply Template
* @copyright (c) 2015 - 2020 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\replytemplate\event;

use phpbb\event\data as event;
use marttiphpbb\replytemplate\service\store;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class posting_listener implements EventSubscriberInterface
{
	protected $store;

	public function __construct(store $store)
	{
		$this->store = $store;
	}

	static public function getSubscribedEvents():array
	{
		return [
			'core.posting_modify_template_vars'
				=> 'core_posting_modify_template_vars',
			'core.viewtopic_modify_quick_reply_template_vars'
				=> 'core_viewtopic_modify_quick_reply_template_vars',
		];
	}

	public function core_posting_modify_template_vars(event $event):void
	{
		$submit = $event['submit'];
		$preview = $event['preview'];
		$load = $event['load'];
		$save = $event['save'];
		$refresh = $event['refresh'];

		if ($submit)
		{
			return;
		}

		if ($preview)
		{
			return;
		}

		if ($load)
		{
			return;
		}

		if ($save)
		{
			return;
		}

		if ($refresh)
		{
			return;
		}

		$mode = $event['mode'];

		if ($mode !== 'reply')
		{
			return;
		}

		$forum_id = $event['forum_id'];

		if (!$this->store->template_is_set($forum_id))
		{
			return;
		}

		$post_data = $event['post_data'];

		if (!empty($post_data['post_text']))
		{
			return;
		}

		$page_data = $event['page_data'];

		$page_data['MESSAGE'] = $this->store->get_template($forum_id);

		$event['page_data'] = $page_data;
	}

	public function core_viewtopic_modify_quick_reply_template_vars(event $event):void
	{
		$topic_data = $event['topic_data'];
		$forum_id = $topic_data['forum_id'];

		if (!$this->store->template_is_set($forum_id))
		{
			return;
		}

		$tpl_ary = $event['tpl_ary'];

		$tpl_ary['MARTTIPHPBB_REPLYTEMPLATE_MESSAGE'] = $this->store->get_template($forum_id);

		$event['tpl_ary'] = $tpl_ary;
	}
}

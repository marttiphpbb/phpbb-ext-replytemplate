<?php

/**
* phpBB Extension - marttiphpbb Topic Template
* @copyright (c) 2015 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\topictemplate\service;

use phpbb\config\db_text as config_text;
use phpbb\cache\driver\driver_interface as cache;

class store
{
	const KEY = 'marttiphpbb_topictemplate';
	const CACHE_KEY = '_' . self::KEY;

	/** @var config_text */
	private $config_text;

	/** @var cache */
	private $cache;
	
	/** @var array */
	private $settings = [];

	public function __construct(config_text $config_text, cache $cache)
	{
		$this->config_text = $config_text;	
		$this->cache = $cache;		
	}

	private function load()
	{
		if ($this->settings)
		{
			return;
		}

		$this->settings = $this->cache->get(self::CACHE_KEY);		
		
		if ($this->settings)
		{
			return;
		}
		
		$this->settings = unserialize($this->config_text->get(self::KEY));
		$this->cache->put(self::CACHE_KEY, $this->settings);
	}

	private function write()
	{
		$this->config_text->set(self::KEY, serialize($this->settings));
		$this->cache->put(self::CACHE_KEY, $this->settings);
	}

	private function set_all(array $settings)
	{
		$this->settings = $settings;
		$this->write();
	}

	private function get_all():array 
	{
		$this->load();
		return $this->settings;
	}

	public function get(int $forum_id):string
	{
		$this->load();
		return $this->settings['forums'][$forum_id] ?? '';
	}

	public function set(int $forum_id, string $template)
	{
		$this->load();

		if (strlen($template) === 0) 
		{
			unset($this->settings['forums'][$forum_id]);
		}
		else
		{
			$this->settings['forums'][$forum_id] = $template;			
		}

		$this->write();
	}

	public function is_set(int $forum_id):bool
	{
		$this->load();
	
		return isset($this->settings['forums'][$forum_id]);
	}

	public function delete_all_but(array $keep_forum_ids)
	{
		$keep_forum_ids = array_fill_keys($keep_forum_ids, true);

		$this->load();

		foreach ($this->settings['forums'] as $forum_id)
		{
			if (!isset($keep_forum_ids[$forum_id]))
			{
				unset($this->settings['forums'][$forum_id]);
			}
		}

		$this->write();
	}
}

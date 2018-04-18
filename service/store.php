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

	public function set_all(array $settings)
	{
		$this->settings = $settings;
		$this->write();
	}

	public function get_all():array 
	{
		$this->load();
		return $this->settings;
	}

	public function get(string $extension_name, string $key):array
	{
		$this->load();
		return $this->settings[$extension_name][$key] ?? [];
	}

	public function set(string $extension_name, string $key, array $template_events)
	{
		$this->load();
		$this->settings[$extension_name][$key] = $template_events;
		$this->write();
	}

	public function remove_extension(string $extension_name)
	{
		$this->load();
		unset($this->settings[$extension_name]);
		$this->write();
	}

	public function get_extensions():array 
	{
		$this->load();
		return array_keys($this->settings);
	}

	public function ext_is_present(string $extension_name)
	{
		$this->load();
		return isset($this->settings[$extension_name]);
	}
}

<?php
/**
* phpBB Extension - marttiphpbb topictemplate
* @copyright (c) 2015 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\topictemplate;

/**
* @ignore
*/

class ext extends \phpbb\extension\base
{
	/**
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	function purge_step($old_state)
	{
		switch ($old_state)
		{
			case '':
				// delete Topic Template data
				$config_text = $this->container->get('config_text');
				$db = $this->container->get('dbal.conn');
				$config_text_table = $this->container->getParameter('tables.config_text');

				// there's no method in the config_text service to retrieve the names with a sql like expression, so we do it with a query here.
				$sql = 'SELECT config_name
					FROM ' . $config_text_table . '
					WHERE config_name ' . $db->sql_like_expression('marttiphpbb_topictemplate_forum' . $db->get_any_char());
				$result = $db->sql_query($sql);
				$topictemplates = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);

				if (sizeof($topictemplates))
				{
					$topictemplates = array_map(function($row){
						return $row['config_name'];
					}, $topictemplates);
					$config_text->delete_array($topictemplates);
				}
				return '1';
				break;
			default:
				return parent::purge_step($old_state);
				break;
		}
	}
}

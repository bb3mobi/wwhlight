<?php
/**
 *
 * @package wwhlight
 * @copyright (c) 2015 alg 
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
*/

namespace alg\wwhlight\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	private $counter_users_total = 0;

	private $counter_users_reg = 0;

	private $counter_users_hidden = 0;

	public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, $phpbb_root_path, $php_ext, \phpbb\auth\auth $auth)
	{
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->config = $config;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->auth = $auth;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.index_modify_page_title'	=> 'index_modify_page_title',
		);
	}

	public function index_modify_page_title()
	{
		global $phpbb_dispatcher;

		$this->user->add_lang_ext('alg/wwhlight', 'wwhlight');

		$user_online_link = $rowset = array();

		$sql_ary = array(
			'SELECT'	=> 'u.user_id, u.username, u.username_clean, u.user_type, u.user_colour, u.user_ip, u.user_lastvisit, u.user_allow_viewonline',
			'FROM'		=> array(
				USERS_TABLE	=> 'u',
			),
			'WHERE'		=> 'user_type <> ' . USER_IGNORE . ' AND DATE(FROM_UNIXTIME (user_lastvisit)) = CURDATE()',
			'ORDER_BY'	=> 'u.user_lastvisit DESC',
		);

		/**
		* Modify SQL query to obtain wwhlight users data
		*
		* @event wwhlight.obtain_users_online_string_sql
		*/
		$vars = array('sql_ary');
		extract($phpbb_dispatcher->trigger_event('wwhlight.obtain_users_online_string_sql', compact($vars)));

		$result = $this->db->sql_query($this->db->sql_build_query('SELECT', $sql_ary));
		$rowset = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		foreach ($rowset as $row)
		{
			if ($row['user_allow_viewonline'])
			{
				$last_visit_time =  sprintf($this->user->lang['WWHLIGHT_LATEST'], $this->user->format_date($row['user_lastvisit'], 'H:i'));
				$username = get_username_string(($row['user_type'] <> USER_IGNORE) ? 'full' : 'no_profile', $row['user_id'], $row['username'], $row['user_colour']);
				$user_online_link[$row['user_id']] = '<span title="' . (($last_visit_time) ? $last_visit_time : '') . '">' . $username . '</span>';
				$this->counter_users_reg++;
			}
			else
			{
				if ($this->auth->acl_get('u_viewonline'))
				{
					$user_online_link[$row['user_id']] = '<em>' . $row['username'] . '</em>';
				}
				$this->counter_users_hidden++;
			}
			$this->counter_users_total++;
		}
		$online_userlist = $this->user->lang['REGISTERED_USERS'] . ' ' . implode(', ', $user_online_link);

		/**
		* Modify wwhlight userlist data
		*
		* @event wwhlight.obtain_users_online_string_modify
		* @var	array	rowset				Array with wwhlight users data
		* @var	array	user_online_link	Array with wwhlight usernames
		* @var	string	online_userlist		String containing users wwhlight list
		*/
		$vars = array(
			'rowset',
			'user_online_link',
			'online_userlist',
		);
		extract($phpbb_dispatcher->trigger_event('wwhlight.obtain_users_online_string_modify', compact($vars)));

		if (!sizeof($user_online_link))
		{
			$online_userlist = $this->user->lang['NO_ONLINE_USERS'];
		}

		$this->template->assign_vars(array(
			'WWHLIGHT_LIST'		=> $online_userlist,
			'WWHLIGHT_DETAILS'	=> $this->obtain_users_string(),
			'WWHLIGHT_RECORD'	=> sprintf($this->user->lang['WWHLIGHT_RECORD'], $this->config['wwhlight_record'], $this->config['wwhlight_record_time']) . '<br />',
		));
	}

	private function obtain_users_string()
	{
		$total_users_string = $this->user->lang('WWHLIGHT_TOTAL', $this->counter_users_total);
		$total_users_string .= $this->user->lang('WWHLIGHT_REG_USERS', $this->counter_users_reg);
		$total_users_string .= $this->user->lang('WWHLIGHT_HIDDEN_USERS', $this->counter_users_hidden);
		return $total_users_string;
	}
}

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

	public function __construct(\phpbb\config\config $config
									, \phpbb\template\template $template
									, \phpbb\user $user
									, \phpbb\db\driver\driver_interface $db
									, $phpbb_root_path, $php_ext
									, \phpbb\auth\auth $auth
								)
	{
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->config = $config;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->auth = $auth;
		$this->counter_users_total = 0;
		$this->counter_users_reg = 0;
		$this->counter_users_hidden = 0;

	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.index_modify_page_title'	=> 'index_modify_page_title',
		);
	}
	 public function index_modify_page_title()
	{
		  $this->user->add_lang_ext('alg/wwhlight', 'wwhlight');
		$username_full = '';
		  $users_list = '';

		$sql = "SELECT user_id, username, username_clean, user_colour, user_type, user_ip, user_lastvisit, user_allow_viewonline  " .
					" FROM  " . USERS_TABLE . 
						" WHERE user_type <>" . USER_IGNORE . 
						" AND DATE(FROM_UNIXTIME (user_lastvisit)) = CURDATE()" . 
						" ORDER BY user_lastvisit desc";
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
				$username_full = get_username_string(( 'full'), $row['user_id'], $row['username'], $row['user_colour']);
				$last_visit_time =  sprintf($this->user->lang['WWHLIGHT_LATEST'], $this->user->format_date($row['user_lastvisit'], 'H:i') );
				$hover_info = $last_visit_time ? ' title="' . $last_visit_time . '"' : '';
				if ($row['user_allow_viewonline'] )
				{
					$users_list .= $this->user->lang['COMMA_SEPARATOR'] . '<span' . $hover_info . '>' . $username_full . '</span>' ;
					$this->counter_users_reg++;
				}
				else
				{
					if ($this->auth->acl_get('u_viewonline'))
					{
						$users_list .= $this->user->lang['COMMA_SEPARATOR'] . '<em' . $hover_info . '>' .$username_full . '</em>' ;
					}
					$this->counter_users_hidden++;
				}
				$this->counter_users_total++;
		}

		$users_list = utf8_substr($users_list, utf8_strlen($this->user->lang['COMMA_SEPARATOR']));
		if ($users_list == '')
		{
			$users_list = $this->user->lang['NO_ONLINE_USERS'];
		}

		$this->template->assign_vars(array(
			'WWHLIGHT_LIST'		=> $this->user->lang['REGISTERED_USERS'] . ' ' . $users_list,
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

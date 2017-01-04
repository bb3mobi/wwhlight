<?php
namespace alg\wwhlight\cron\task;

class wwhlight_calc_record extends \phpbb\cron\task\base
{
	 public function __construct(\phpbb\config\config $config
														  , \phpbb\db\driver\driver_interface $db
														  , \phpbb\auth\auth $auth
														  )
	{
		$this->config = $config;
		  $this->db = $db;
		$this->auth = $auth;
	}
	 	/**
	* Runs this cron task.
	*
	* @return null
	*/
	public function run()
	{
		$sql = "SELECT COUNT(*)  as counter " .
					" FROM  " . USERS_TABLE . 
					" WHERE user_type <>" . USER_IGNORE . 
					" AND DATE(FROM_UNIXTIME (user_lastvisit)) >= SUBDATE(CURDATE(),1)";
		$result = $this->db->sql_query($sql);
		$counter = (int) $this->db->sql_fetchfield('counter');
		$this->db->sql_freeresult($result);
		if($counter > $this->config['wwhlight_record'])
		{
				$this->config->set('wwhlight_record', $counter);
				$this->config->set('wwhlight_record_time', date("d/m/Y", strtotime( '-1 days' ) ) . ' - ' .  date("d/m/Y"));
		}
		$this->config->set('wwhlight_calc_record_last_gc', time());
	}
	/**
		* Returns whether this cron task can run, given current board configuration.
		*
		* If warnings are set to never expire, this cron task will not run.
		*
		* @return bool
	*/
	public function is_runnable()
	{
		return true;
	}

	/**
	* Returns whether this cron task should run now, because enough time
	* has passed since it was last.
	* @return bool
	*/
	public function should_run()
	{
		return $this->config['wwhlight_calc_record_last_gc'] < time() - $this->config['wwhlight_calc_record_gc'];
	}
}


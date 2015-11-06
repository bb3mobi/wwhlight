<?php
/**
*
* @package - wwhlight 
* @copyright (c) alg
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace alg\wwhlight\migrations;

class v_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['wwhlight']) && version_compare($this->config['wwhlight_version'], '1.0.*', '>=');
		return false;

	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}
	public function update_schema()
	{
		return array(
		);
	}

	public function revert_schema()
	{
		return array(
		);
	}
	 
	 public function update_data()
	 {
		  return array(
			// Add new configs
			// Current version
			array('config.add', array('wwhlight_version', '1.0.0')),
			array('config.add', array('wwhlight_record', '1')),
			array('config.add', array('wwhlight_record_time', '')),
				array('config.add', array('wwhlight_calc_record_gc', (60 * 60 * 24), '1')), 
				array('config.add', array('wwhlight_calc_record_last_gc', 0, '0')), 

		 );

	 }
	public function revert_data()
	{
		return array(
			// remove from configs
			array('config.remove', array('wwhlight_record')),
			array('config.remove', array('wwhlight_record_time')),
			array('config.remove', array('wwhlight_calc_record_gc')),
			array('config.remove', array('wwhlight_calc_record_last_gc')),
			// Current version
			array('config.remove', array('wwhlight_version')),

		);
	}
	 

}
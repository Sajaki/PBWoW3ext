<?php
/**
 *
 * @package PBWoW Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwow\migrations;

class release_3_2_0_data extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\paybas\pbwow\migrations\release_3_2_0');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'pbwow_update_data'))),
		);
	}

	public function pbwow_update_data()
	{
		$standard_ary = array(
			array(
				'config_name' => 'topbar_code',
				'old_config_value' => '<li class="icon1 small-icon" data-skip-responsive="true"><strong>Hi there! This is a welcome message.</strong></li>
<li class="icon2 small-icon link"><a href="http://www.avathar.be/bbdkp/">avathar.be</a></li>
<li class="icon3 small-icon link"><a href="https://www.phpbb.com/">phpBB</a></li>
<li class="icon4 small-icon link rightside"><a href="#">On the right</a></li>',
				'new_config_value' =>'<li data-last-responsive="true" class="leftside">
<i class="icon fa-cubes fa-fw" aria-hidden="true"></i>&nbsp;<span><strong>Hi there! This is a welcome message</strong></span>
</li>
<li data-last-responsive="true" class="leftside"  ><a href="http://www.avathar.be" title="avathar.be" role="menuitem">
<i class="icon fa-heart fa-fw" aria-hidden="true"></i><span>avathar.be</span></a>
</li>
<li data-last-responsive="true" class="leftside"><a href="https://www.phpbb.com" title="phpBB" role="menuitem">
<i class="icon fa-globe fa-fw" aria-hidden="true"></i><span>phpBB</span></a>
</li>
<li data-last-responsive="true" class="rightside"><a href="#" title="On the right" role="menuitem">
<i class="icon fa-hand-o-right fa-fw" aria-hidden="true"></i><span>On the right</span></a>
</li>',
				'config_default' => '',
			),

			array(
				'config_name' => 'headerlinks_code',
				'old_config_value' => '<li class="icon-portal small-icon"><a href="http://www.phpbb.com/" target="_blank">phpBB</a></li>',
				'new_config_value' => '<li data-last-responsive="true" class="leftside">
<a href="http://www.phpbb.com/" title="link" target="_blank" role="menuitem">
<i class="icon fa-question-circle fa-fw" aria-hidden="true"></i><span>phpBB</span></a>
</li>',
				'config_default' => '',
			),
		);

		$remove_ary = array(
			'avatars_enable',
			'avatars_path',
			'smallranks_enable',
			'bnetchars_enable',
			'bnet_apikey',
			'bnetchars_cachetime',
			'bnetchars_timeout',
		);

		$sql = 'DELETE FROM ' . $this->table_prefix . 'pbwow3_config WHERE ' . $this->db->sql_in_set('config_name', $remove_ary);
		$this->db->sql_query($sql);

		foreach ($standard_ary as $key => $standardvalue)
		{
			$sql = 'SELECT * FROM ' . $this->table_prefix . "pbwow3_config WHERE config_name = '" . $standardvalue['config_name'] . "'";
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				if ($standardvalue['config_name'] == $row['config_name'])
				{
					$sql = 'UPDATE ' . $this->table_prefix . "pbwow3_config SET config_value = '" . $standardvalue['new_config_value']  . "'
					WHERE config_name = '" . $row['config_name'] . "'";

					$this->db->sql_query($sql);

				}
			}
			$this->db->sql_freeresult($result);
		}



	}

}

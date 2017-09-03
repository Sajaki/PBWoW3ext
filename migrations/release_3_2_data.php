<?php
/**
 *
 * @package PBWoW Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwowext\migrations;

class release_3_2_data extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\paybas\pbwowext\migrations\release_3_2_schema');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'pbwow_populate_data'))),
		);
	}

	public function pbwow_populate_data()
	{
		if ($this->db_tools->sql_table_exists($this->table_prefix . 'pbwowext_config'))
		{
			$sql = 'SELECT * FROM ' . $this->table_prefix . 'pbwowext_config';
			$result = $this->db->sql_query_limit($sql, 1);
			$row = $this->db->sql_fetchrow($result);

			if (!empty($row))
			{
				return;
			}
		}
		else
		{
			return;
		}

		$sql_ary = array(
			// Ads
			array(
				'config_name' => 'ads_index_enable',
				'config_value' => '1',
				'config_default' => '1',
			),
			array(
				'config_name' => 'ads_index_code',
				'config_value' => '<a class="donate-button" href="http://www.avathar.be/bbdkp/app.php/page/donate"></a>',
				'config_default' => '<a class="donate-button" href="http://www.avathar.be/bbdkp/app.php/page/donate"></a>',
			),

			// Global styling
			array(
				'config_name' => 'topbar_enable',
				'config_value' => '1',
				'config_default' => '1',
			),
			array(
				'config_name' => 'topbar_code',
				'config_value' =>'<li data-last-responsive="true" class="leftside">
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
				'config_name' => 'topbar_fixed',
				'config_value' => '0',
				'config_default' => '0',
			),
			array(
				'config_name' => 'videobg_enable',
				'config_value' => '1',
				'config_default' => '1',
			),
			array(
				'config_name' => 'videobg_allpages',
				'config_value' => '0',
				'config_default' => '0',
			),
			array(
				'config_name' => 'fixedbg',
				'config_value' => '0',
				'config_default' => '0',
			),

			// Logo
			array(
				'config_name' => 'logo_enable',
				'config_value' => '0',
				'config_default' => '0',
			),
			array(
				'config_name' => 'logo_src',
				'config_value' => 'images/logo.png',
				'config_default' => 'images/logo.png',
			),
			array(
				'config_name' => 'logo_size_width',
				'config_value' => '300',
				'config_default' => '300',
			),
			array(
				'config_name' => 'logo_size_height',
				'config_value' => '180',
				'config_default' => '180',
			),
			array(
				'config_name' => 'logo_margins',
				'config_value' => '10px 10px 25px 10px',
				'config_default' => '10px 10px 25px 10px',
			),

			// Miscellaneous
			array(
				'config_name' => 'headerlinks_enable',
				'config_value' => '1',
				'config_default' => '1',
			),
			array(
				'config_name' => 'headerlinks_code',
				'config_value' => '<li data-last-responsive="true" class="leftside">
<a href="http://www.phpbb.com/" title="link" target="_blank" role="menuitem">
<i class="icon fa-question-circle fa-fw" aria-hidden="true"></i><span>phpBB</span></a>
</li>',
				'config_default' => '',
			),

		);

		$sql = $this->db->sql_multi_insert($this->table_prefix . 'pbwowext_config', $sql_ary);
		$this->sql_query($sql);
	}
}

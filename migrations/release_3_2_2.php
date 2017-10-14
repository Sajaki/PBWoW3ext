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

class release_3_2_2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['pbwowext_version']) && version_compare($this->config['pbwowext_version'], '3.2.2', '>=');
	}

	static public function depends_on()
	{
		return array('\paybas\pbwowext\migrations\release_3_2_1_data');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('pbwowext_version', '3.2.2')),

			);
	}
}

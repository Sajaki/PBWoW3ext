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

class release_3_2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['pbwowext_version']) && version_compare($this->config['pbwowext_version'], '3.2', '>=');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('pbwowext_version', '3.2')),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_PBWOWEXT_CATEGORY'
			)),

			array('module.add', array(
				'acp',
				'ACP_PBWOWEXT_CATEGORY',
				array(
					'module_basename'	=> '\paybas\pbwowext\acp\pbwow_module',
					'modes'	=> array('config'),
				),
			)),
		);
	}
}

<?php
/**
 *
 * @package PBWoW Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwow\migrations;

class release_3_2 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\paybas\pbwow\migrations\release_3_0_5');
	}

	public function update_schema()
	{
		return array(
				'drop_tables' => array(
					$this->table_prefix . 'pbwow3_chars',
				),
		);
	}
	public function update_data()
	{
		return array(
			array('module.remove', array(
				'acp',
				'ACP_PBWOW3_CATEGORY',
				array(
					'module_basename'	=> '\paybas\pbwow\acp\pbwow_module',
					'modes'	=> array('overview'),
				),
			)),

		);
	}

	public function revert_schema()
	{
		return array(
				'add_tables' => array(
					$this->table_prefix . 'pbwow3_chars' => array(
						'COLUMNS' => array(
							'id'                 => array('UINT', null, 'auto_increment'),
							'user_id'            => array('UINT', null),
							'updated'            => array('BINT', 0),
							'tries'              => array('TINT:3', 0),
							'game'               => array('VCHAR', ''),
							'last_modified'      => array('BINT', 0),
							'name'               => array('VCHAR', ''),
							'realm'              => array('VCHAR', ''),
							'battlegroup'        => array('VCHAR', ''),
							'class'              => array('TINT:3', 0),
							'race'               => array('TINT:3', 0),
							'gender'             => array('TINT:3', 0),
							'level'              => array('TINT:3', 0),
							'achievement_points' => array('UINT', 0),
							'url'                => array('VCHAR', ''),
							'avatar'             => array('VCHAR', ''),
							'avatar_url'         => array('VCHAR', ''),
							'calc_class'         => array('VCHAR', ''),
							'total_hk'           => array('UINT', 0),
							'guild'              => array('VCHAR', ''),
						),
						'PRIMARY_KEY'	=> 'id',
						'KEYS'            => array(
							'user_id'    => array('INDEX', 'user_id'),
						),
					),
			)
		);
	}


}

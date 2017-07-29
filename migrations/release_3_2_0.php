<?php
/**
 *
 * @package PBWoW Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwow\migrations;

class release_3_2_0 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\paybas\pbwow\migrations\release_3_0_5');
	}
}

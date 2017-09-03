<?php
/**
 *
 * @package PBWoW Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwowext\acp;

class pbwow_info
{
	function module()
	{
		return array(
			'filename' => '\paybas\pbwowext\acp\pbwow_module',
			'title'    => 'ACP_PBWOWEXT_CATEGORY',
			'modes'    => array(
				'config'   => array('title' => 'ACP_PBWOWEXT_CONFIG', 'auth' => 'ext_paybas/pbwowext && acl_a_board', 'cat' => array('ACP_PBWOWEXT_CATEGORY')),
			),
		);
	}
}

<?php
/**
 *
 * @package PBWoW Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwow\acp;

/**
 * Class pbwow_module
 *
 * @package paybas\pbwow\acp
 */
class pbwow_module
{
	public $u_action;

	protected $fields_table;
	protected $pbwow_config_table;
	protected $pbwow_config;
	protected $pbwow_chars_table;

	/**
	 * @param $id
	 * @param $mode
	 */
	public function main($id, $mode)
	{
		global $cache, $config, $request, $template, $user;
		global $phpbb_log, $phpbb_root_path, $table_prefix, $phpbb_container;

		$user->add_lang('acp/board');
		$this->tpl_name = 'acp_pbwow3';

		$db_tools = $phpbb_container->get('dbal.tools');

		$this->fields_table = $phpbb_container->getParameter('tables.profile_fields');
		$this->pbwow_config_table = $phpbb_container->getParameter('tables.pbwow3_config');
		$this->pbwow_chars_table = $phpbb_container->getParameter('tables.pbwow3_chars');

		$allow_curl = function_exists('curl_init');
		$legacy_dbtable1 = defined('PBWOW_CONFIG_TABLE') ? PBWOW_CONFIG_TABLE : '';
		$legacy_dbtable2 = defined('PBWOW2_CONFIG_TABLE') ? PBWOW2_CONFIG_TABLE : '';

		$constantsokay = $dbokay = $legacy_constants = $legacy_db_active = $chars_constokay = $chars_dbokay = $new_config = $ext_version = false;

		// Check if constants have been set correctly
		// if yes, check if the config table exists
		// if yes, load the config variables
		if ($this->pbwow_config_table == ($table_prefix . 'pbwow3_config'))
		{
			$constantsokay = true;

			if ($db_tools->sql_table_exists($this->pbwow_config_table))
			{
				$dbokay = true;
				$this->get_pbwow_config();
				$new_config = $this->pbwow_config;
			}
		}

		if ($this->pbwow_chars_table == ($table_prefix . 'pbwow3_chars'))
		{
			$chars_constokay = true;

			if ($db_tools->sql_table_exists($this->pbwow_chars_table))
			{
				$chars_dbokay = true;
			}
		}

		// Detect if a game has been enabled/disabled in the overview. This must be done before we retrieve the $cplist
		$cpf_game_toggle = $request->variable('game', '');
		if (!empty($cpf_game_toggle))
		{
			$activate = $request->variable('enable', '');
			$this->toggle_game_cpf($cpf_game_toggle, $activate);
		}

		// Detect Battle.net characters table flush
		$charsdb_flush = $request->variable('charsdb_flush', '');
		if ($charsdb_flush && $chars_dbokay)
		{
			$this->charsdb_flush();
		}

		if ($mode == 'overview')
		{
			// Get the PBWoW extension version from the composer.json file
			$ext_manager = $phpbb_container->get('ext.manager');
			$ext_meta_manager = $ext_manager->create_extension_metadata_manager('paybas/pbwow', $template);
			$ext_meta_data = $ext_meta_manager->get_metadata('version');
			$ext_version = isset($ext_meta_data['version']) ? $ext_meta_data['version'] : '';

			$cpflist = $this->get_profile_fields_list();

			$style_root = ($phpbb_root_path . 'styles/pbwow3/');

			// Get the PBWoW style version from the style.cfg file
			if (file_exists($style_root . 'style.cfg'))
			{
				$values = parse_cfg_file($style_root . 'style.cfg');
				$style_version = (isset($values['style_version'])) ? $values['style_version'] : '';
			}

			$versions = $this->version_check($request->variable('versioncheck_force', false));

			// Check if old constants are still being used
			if (!empty($legacy_dbtable1) || !empty($legacy_dbtable2))
			{
				$legacy_constants = true;
			}

			// Check if old table still exists
			if ($db_tools->sql_table_exists($legacy_dbtable1)
				|| $db_tools->sql_table_exists($table_prefix . 'pbwow_config')
				|| $db_tools->sql_table_exists($legacy_dbtable2)
				|| $db_tools->sql_table_exists($table_prefix . 'pbwow2_config'))
			{
				$legacy_db_active = true;
			}
		}

		/**
		 *    Config vars
		 */
		switch ($mode)
		{
			case 'overview':
				$display_vars = array(
					'title' => 'PBWOW_OVERVIEW_TITLE',
					'vars'  => array()
				);
				break;
			case 'config':
				$display_vars = array(
					'title' => 'PBWOW_CONFIG_TITLE',
					'vars'  => array(
						'legend1'             => 'PBWOW_LOGO',
						'logo_size_width'     => array('lang' => 'PBWOW_LOGO_SIZE', 'validate' => 'int:0', 'type' => false, 'method' => false, 'explain' => false),
						'logo_size_height'    => array('lang' => 'PBWOW_LOGO_SIZE', 'validate' => 'int:0', 'type' => false, 'method' => false, 'explain' => false),
						'logo_enable'         => array('lang' => 'PBWOW_LOGO_ENABLE', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true),
						'logo_src'            => array('lang' => 'PBWOW_LOGO_SRC', 'validate' => 'string', 'type' => 'text:20:255', 'explain' => true),
						'logo_size'           => array('lang' => 'PBWOW_LOGO_SIZE', 'validate' => 'int:0', 'type' => 'dimension:0', 'explain' => true, 'append' => ' ' . $user->lang['PIXEL']),
						'logo_margins'        => array('lang' => 'PBWOW_LOGO_MARGINS', 'validate' => 'string', 'type' => 'text:20:20', 'explain' => true),

						'legend2'             => 'PBWOW_TOPBAR',
						'topbar_enable'       => array('lang' => 'PBWOW_TOPBAR_ENABLE', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true),
						'topbar_code'         => array('lang' => 'PBWOW_TOPBAR_CODE', 'type' => 'textarea:6:6', 'explain' => true),
						'topbar_fixed'        => array('lang' => 'PBWOW_TOPBAR_FIXED', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),

						'legend3'             => 'PBWOW_HEADERLINKS',
						'headerlinks_enable'  => array('lang' => 'PBWOW_HEADERLINKS_ENABLE', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true),
						'headerlinks_code'    => array('lang' => 'PBWOW_HEADERLINKS_CODE', 'type' => 'textarea:6:6', 'explain' => true),

						'legend4'             => 'PBWOW_VIDEOBG',
						'videobg_enable'      => array('lang' => 'PBWOW_VIDEOBG_ENABLE', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true),
						'videobg_allpages'    => array('lang' => 'PBWOW_VIDEOBG_ALLPAGES', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),
						'fixedbg'             => array('lang' => 'PBWOW_FIXEDBG', 'validate' => 'bool', 'type' => 'radio:yes_no', 'explain' => true),

						'legend5'             => 'PBWOW_AVATARS',
						'avatars_enable'      => array('lang' => 'PBWOW_AVATARS_ENABLE', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true),
						'avatars_path'        => array('lang' => 'PBWOW_AVATARS_PATH', 'validate' => 'string', 'type' => 'text:20:255', 'explain' => true),
						'smallranks_enable'   => array('lang' => 'PBWOW_SMALLRANKS_ENABLE', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true),

						'legend6'             => 'PBWOW_BNETCHARS',
						'bnetchars_enable'    => array('lang' => 'PBWOW_BNETCHARS_ENABLE', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true),
						'bnet_apikey'         => array('lang' => 'PBWOW_BNET_APIKEY', 'validate' => 'string', 'type' => 'text:32:64', 'explain' => true),
						'bnetchars_cachetime' => array('lang' => 'PBWOW_BNETCHARS_CACHETIME', 'validate' => 'int:0', 'type' => 'text:6:6', 'explain' => true, 'append' => ' ' . $user->lang['SECONDS']),
						'bnetchars_timeout'   => array('lang' => 'PBWOW_BNETCHARS_TIMEOUT', 'validate' => 'int:0', 'type' => 'text:1:1', 'explain' => true, 'append' => ' ' . $user->lang['SECONDS']),

						'legend7'             => 'PBWOW_ADS_INDEX',
						'ads_index_enable'    => array('lang' => 'PBWOW_ADS_INDEX_ENABLE', 'validate' => 'bool', 'type' => 'radio:enabled_disabled', 'explain' => true),
						'ads_index_code'      => array('lang' => 'PBWOW_ADS_INDEX_CODE', 'type' => 'textarea:6:6', 'explain' => true),
					)
				);
				break;
			default:
				$display_vars = array(
					'title' => 'ACP_PBWOW3_CATEGORY',
					'vars'  => array()
				);
				break;
		}

		$submit = $request->is_set_post('submit');

		$cfg_array = (isset($_REQUEST['config'])) ? utf8_normalize_nfc($request->variable('config', array('' => ''), true)) : $new_config;
		$error = array();

		// We validate the complete config if we want
		validate_config_vars($display_vars['vars'], $cfg_array, $error);

		// Do not write values if there is an error
		if (sizeof($error))
		{
			$submit = false;
		}

		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to... and then write to config
		foreach ($display_vars['vars'] as $config_name => $null)
		{
			if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
			{
				continue;
			}

			$new_config[$config_name] = $config_value = $cfg_array[$config_name];

			if ($submit)
			{
				$this->set_pbwow_config($config_name, $config_value);

				// Enable/disable Battle.net profile fields when the API is enabled/disabled
				if ($config_name == 'bnetchars_enable')
				{
					$this->toggle_game_cpf('bnet', $config_value);
				}
			}
		}

		if ($submit)
		{
			if ($mode != 'overview')
			{
				$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'LOG_PBWOW_CONFIG');
				$cache->purge();
				trigger_error($user->lang['CONFIG_UPDATED'] . adm_back_link($this->u_action));
			}
		}

		$this->page_title = $display_vars['title'];
		$title_explain = $user->lang[$display_vars['title'] . '_EXPLAIN'];

		$template->assign_vars(array(
				'L_TITLE'              => $user->lang[$display_vars['title']],
				'L_TITLE_EXPLAIN'      => $title_explain,

				'S_ERROR'              => (sizeof($error)) ? true : false,
				'ERROR_MSG'            => implode('<br />', $error),

				'S_CONSTANTSOKAY'      => ($constantsokay) ? true : false,
				'PBWOW_DBTABLE'        => $this->pbwow_config_table,
				'S_DBOKAY'             => ($dbokay) ? true : false,
				'S_CURL_DANGER'        => isset($this->pbwow_config['bnetchars_enable']) && $this->pbwow_config['bnetchars_enable'] && !$allow_curl,

				'L_PBWOW_DB_GOOD'      => sprintf($user->lang['PBWOW_DB_GOOD'], $this->pbwow_config_table),
				'L_PBWOW_DB_BAD'       => sprintf($user->lang['PBWOW_DB_BAD'], $this->pbwow_config_table),

				'L_PBWOW_CHARSDB_GOOD' => sprintf($user->lang['PBWOW_CHARSDB_GOOD'], $this->pbwow_chars_table),
				'L_PBWOW_CHARSDB_BAD'  => sprintf($user->lang['PBWOW_CHARSDB_BAD'], $this->pbwow_chars_table),

				'U_ACTION'             => $this->u_action,
			)
		);

		if ($mode == 'overview')
		{
			$pb_bnet_host =			(isset($cpflist['pb_bnet_host']) 		&& $cpflist['pb_bnet_host']['field_active'] 		&& !$cpflist['pb_bnet_host']['field_no_view']) ? true : false;
			$pb_bnet_realm =		(isset($cpflist['pb_bnet_realm']) 		&& $cpflist['pb_bnet_realm']['field_active'] 		&& !$cpflist['pb_bnet_realm']['field_no_view']) ? true : false;
			$pb_bnet_name =			(isset($cpflist['pb_bnet_name']) 		&& $cpflist['pb_bnet_name']['field_active'] 		&& !$cpflist['pb_bnet_name']['field_no_view']) ? true : false;
			$pb_bnet_url =			(isset($cpflist['pb_bnet_url']) 		&& $cpflist['pb_bnet_url']['field_active']			&& !$cpflist['pb_bnet_url']['field_no_view']) ? true : false;
			$pb_bnet_avatar =		(isset($cpflist['pb_bnet_avatar']) 		&& $cpflist['pb_bnet_avatar']['field_active'] 		&& !$cpflist['pb_bnet_avatar']['field_no_view']) ? true : false;
			$pb_wow_race =			(isset($cpflist['pb_wow_race']) 		&& $cpflist['pb_wow_race']['field_active'] 			&& !$cpflist['pb_wow_race']['field_no_view']) ? true : false;
			$pb_wow_gender =		(isset($cpflist['pb_wow_gender']) 		&& $cpflist['pb_wow_gender']['field_active'] 		&& !$cpflist['pb_wow_gender']['field_no_view']) ? true : false;
			$pb_wow_class =			(isset($cpflist['pb_wow_class']) 		&& $cpflist['pb_wow_class']['field_active'] 		&& !$cpflist['pb_wow_class']['field_no_view']) ? true : false;
			$pb_wow_level =			(isset($cpflist['pb_wow_level']) 		&& $cpflist['pb_wow_level']['field_active'] 		&& !$cpflist['pb_wow_level']['field_no_view']) ? true : false;
			$pb_wow_guild =			(isset($cpflist['pb_wow_guild']) 		&& $cpflist['pb_wow_guild']['field_active'] 		&& !$cpflist['pb_wow_guild']['field_no_view']) ? true : false;
			$pb_diablo_class =		(isset($cpflist['pb_diablo_class']) 	&& $cpflist['pb_diablo_class']['field_active'] 		&& !$cpflist['pb_diablo_class']['field_no_view']) ? true : false;
			$pb_diablo_gender =		(isset($cpflist['pb_diablo_gender']) 	&& $cpflist['pb_diablo_gender']['field_active'] 	&& !$cpflist['pb_diablo_gender']['field_no_view']) ? true : false;
			$pb_diablo_follower = 	(isset($cpflist['pb_diablo_follower']) 	&& $cpflist['pb_diablo_follower']['field_active'] 	&& !$cpflist['pb_diablo_follower']['field_no_view']) ? true : false;
			$pb_wildstar_race =		(isset($cpflist['pb_wildstar_race']) 	&& $cpflist['pb_wildstar_race']['field_active'] 	&& !$cpflist['pb_wildstar_race']['field_no_view']) ? true : false;
			$pb_wildstar_gender =	(isset($cpflist['pb_wildstar_gender']) 	&& $cpflist['pb_wildstar_gender']['field_active'] 	&& !$cpflist['pb_wildstar_gender']['field_no_view']) ? true : false;
			$pb_wildstar_class =	(isset($cpflist['pb_wildstar_class']) 	&& $cpflist['pb_wildstar_class']['field_active'] 	&& !$cpflist['pb_wildstar_class']['field_no_view']) ? true : false;
			$pb_wildstar_path =		(isset($cpflist['pb_wildstar_path']) 	&& $cpflist['pb_wildstar_path']['field_active'] 	&& !$cpflist['pb_wildstar_path']['field_no_view']) ? true : false;

			$pb_wow_enabled = 		$pb_wow_race && $pb_wow_gender && $pb_wow_class && $pb_wow_level && $pb_wow_guild;
			$pb_diablo_enabled = 	$pb_diablo_class && $pb_diablo_gender && $pb_diablo_follower;
			$pb_wildstar_enabled = 	$pb_wildstar_race && $pb_wildstar_gender && $pb_wildstar_class && $pb_wildstar_path;

			$pb_wow_activate_url = $this->u_action . '&game=wow&enable=' . ($pb_wow_enabled ? '0' : '1');
			$pb_diablo_activate_url = $this->u_action . '&game=diablo&enable=' . ($pb_diablo_enabled ? '0' : '1');
			$pb_wildstar_activate_url = $this->u_action . '&game=wildstar&enable=' . ($pb_wildstar_enabled ? '0' : '1');

			$pb_charsdb_flush_url = $this->u_action . '&charsdb_flush=1';

			$template->assign_vars(array(
					'S_INDEX'               => true,

					'S_CHECK_V'             => (empty($versions)) ? false : true,
					'EXT_VERSION'           => $ext_version,
					'EXT_VERSION_V'         => (isset($versions['current'])) ? $versions['current'] : '',
					'STYLE_VERSION'         => (isset($style_version)) ? $style_version : '',
					'STYLE_VERSION_V'       => (isset($versions['style_version'])) ? $versions['style_version'] : '',
					'U_VERSIONCHECK_FORCE'  => append_sid($this->u_action . '&amp;versioncheck_force=1'),
					'S_ALLOW_CURL'          => $allow_curl,

					'S_CPF_ON_MEMBERLIST'   => ($config['load_cpf_memberlist'] == 1) ? true : false,
					'S_CPF_ON_VIEWPROFILE'  => ($config['load_cpf_viewprofile'] == 1) ? true : false,
					'S_CPF_ON_VIEWTOPIC'    => ($config['load_cpf_viewtopic'] == 1) ? true : false,

					'S_PB_WOW'              => $pb_wow_enabled,
					'S_PB_DIABLO'           => $pb_diablo_enabled,
					'S_PB_WILDSTAR'         => $pb_wildstar_enabled,
					'U_PB_WOW'              => $pb_wow_activate_url,
					'U_PB_DIABLO'           => $pb_diablo_activate_url,
					'U_PB_WILDSTAR'         => $pb_wildstar_activate_url,

					'S_BNETCHARS_ACTIVE'    => (isset($this->pbwow_config['bnetchars_enable']) && $this->pbwow_config['bnetchars_enable']) ? true : false,
					'S_BNETCHARS_CONSTOKAY' => ($chars_constokay) ? true : false,
					'S_BNETCHARS_DBOKAY'    => ($chars_dbokay) ? true : false,
					'BNET_APIKEY'			=> isset($this->pbwow_config['bnet_apikey']) ? $this->pbwow_config['bnet_apikey'] : false,
					'U_CHARSDB_FLUSH'       => $pb_charsdb_flush_url,
					'S_PB_BNET_HOST'        => $pb_bnet_host,
					'S_PB_BNET_REALM'       => $pb_bnet_realm,
					'S_PB_BNET_NAME'        => $pb_bnet_name,
					'S_PB_BNET_URL'         => $pb_bnet_url,
					'S_PB_BNET_AVATAR'      => $pb_bnet_avatar,
					'S_PB_WOW_RACE'         => $pb_wow_race,
					'S_PB_WOW_GENDER'       => $pb_wow_gender,
					'S_PB_WOW_CLASS'        => $pb_wow_class,
					'S_PB_WOW_LEVEL'        => $pb_wow_level,
					'S_PB_WOW_GUILD'        => $pb_wow_guild,
					'S_PB_DIABLO_CLASS'     => $pb_diablo_class,
					'S_PB_DIABLO_GENDER'    => $pb_diablo_gender,
					'S_PB_DIABLO_FOLLOWER'  => $pb_diablo_follower,
					'S_PB_WILDSTAR_RACE'    => $pb_wildstar_race,
					'S_PB_WILDSTAR_GENDER'  => $pb_wildstar_gender,
					'S_PB_WILDSTAR_CLASS'   => $pb_wildstar_class,
					'S_PB_WILDSTAR_PATH'    => $pb_wildstar_path,

					'S_LEGACY_CONSTANTS'    => $legacy_constants,
					'S_LEGACY_DB_ACTIVE'    => $legacy_db_active,
				)
			);
		}

		// Output relevant page
		foreach ($display_vars['vars'] as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== false)
			{
				$template->assign_block_vars('options', array(
						'S_LEGEND' => true,
						'LEGEND'   => (isset($user->lang[$vars])) ? $user->lang[$vars] : $vars)
				);

				continue;
			}

			$type = explode(':', $vars['type']);

			$l_explain = '';
			if ($vars['explain'] && isset($vars['lang_explain']))
			{
				$l_explain = (isset($user->lang[$vars['lang_explain']])) ? $user->lang[$vars['lang_explain']] : $vars['lang_explain'];
			}
			else if ($vars['explain'])
			{
				$l_explain = (isset($user->lang[$vars['lang'] . '_EXPLAIN'])) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '';
			}

			$content = build_cfg_template($type, $config_key, $new_config, $config_key, $vars);

			if (empty($content))
			{
				continue;
			}

			$template->assign_block_vars('options', array(
					'KEY'           => $config_key,
					'TITLE'         => (isset($user->lang[$vars['lang']])) ? $user->lang[$vars['lang']] : $vars['lang'],
					'S_EXPLAIN'     => $vars['explain'],
					'TITLE_EXPLAIN' => $l_explain,
					'CONTENT'       => $content,
				)
			);

			unset($display_vars['vars'][$config_key]);
		}
	}

##################################################
####                                          ####
####              Board Settings              ####
####                                          ####
##################################################

	/**
	 * Get a list of all available profile fields, so
	 * we can check if they are configured correctly.
	 */
	function get_profile_fields_list()
	{
		global $db;

		$sql = 'SELECT *
			FROM ' . $this->fields_table . "
			WHERE field_active = 1
			ORDER BY field_order";
		$result = $db->sql_query($sql);

		$profile_fields_list = array();

		while ($row = $db->sql_fetchrow($result))
		{
			$profile_fields_list[$row['field_name']] = $row;
		}
		$db->sql_freeresult($result);

		return $profile_fields_list;
	}

	/**
	 * Toggle profile fields of individual games
	 */
	function toggle_game_cpf($game, $enable)
	{
		global $db;

		$value = $enable ? '1' : '0';
		$sql = 'UPDATE ' . $this->fields_table . "
			SET field_active = '" . $value . "'
			WHERE field_ident " . $db->sql_like_expression($db->get_any_char() . 'pb_' . $game . $db->get_any_char());
		$db->sql_query($sql);
	}

	/**
	 * Clears/flushes the Battle.net API characters table
	 */
	function charsdb_flush()
	{
		global $db;

		$db->sql_query('DELETE FROM ' . $this->pbwow_chars_table . ' WHERE 1=1');
	}

##################################################
####                                          ####
####             Config Functions             ####
####                                          ####
##################################################

	/**
	 * Get PBWoW config.
	 */
	function get_pbwow_config()
	{
		global $cache, $db;

		if (($this->pbwow_config = $cache->get('pbwow_config')) !== true)
		{
			$this->pbwow_config = array();

			$sql = 'SELECT * FROM ' . $this->pbwow_config_table;
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$this->pbwow_config[$row['config_name']] = $row['config_value'];
			}
			$db->sql_freeresult($result);

			$cache->put('pbwow_config', $this->pbwow_config);
		}
	}

	/**
	 * Set config value (and cache it). Creates missing config entry.
	 */
	function set_pbwow_config($config_name, $config_value)
	{
		global $db;

		$sql = 'UPDATE ' . $this->pbwow_config_table . "
			SET config_value = '" . $db->sql_escape($config_value) . "'
			WHERE config_name = '" . $db->sql_escape($config_name) . "'";
		$db->sql_query($sql);

		if (!$db->sql_affectedrows() && !isset($this->pbwow_config[$config_name]))
		{
			$sql = 'INSERT INTO ' . $this->pbwow_config_table . ' ' .
				$db->sql_build_array('INSERT', array(
						'config_name'    => $config_name,
						'config_value'   => $config_value,
						'config_default' => '')
				);
			$db->sql_query($sql);
		}
		$this->pbwow_config[$config_name] = $config_value;
	}

	/**
	 * Obtains the latest version information.
	 */
	public function version_check($force_update = false)
	{
		global $cache, $config, $user;

		$host = 'www.avathar.be';
		$directory = '/versioncheck';
		$filename = 'pbwowext.json';
		$port = 80;
		$timeout = 5;

		$latest_version_a = $cache->get('pbwow_versioncheck');
		if ($latest_version_a === false || $force_update)
		{
			$errstr = '';
			$errno = 0;
			$version_helper = new \phpbb\version_helper($cache, $config, new \phpbb\file_downloader(), $user);
			$version_helper->set_current_version($cache->get('pbwow_versioncheck'));
			$version_helper->set_file_location($host, $directory, $filename, false);
			$version_helper->force_stability('stable');
			$versions = $version_helper->get_versions_matching_stability($force_update, false);

			$latest_version_a  = $versions['3.0'];
			$cache->put('pbwow_versioncheck', $latest_version_a);
		}
		return $latest_version_a;
	}
}

<?php
/**
 *
 * @package PBWoW Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwowext\core;

use phpbb\cache\service;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\db\tools;
use phpbb\event\dispatcher_interface;

class pbwowstyle
{
	/** @var config */
	protected $config;

	/** @var service */
	protected $cache;

	/** @var driver_interface */
	protected $db;

	/** @var tools */
	protected $db_tools;

	/** @var dispatcher_interface */
	protected $dispatcher;

	/** @var \phpbb\extension\manager */
	protected $extension_manager;

	/** @var \phpbb\profilefields\manager */
	protected $profilefields_manager;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string PHP extension */
	protected $phpEx;

	protected $pbwow_config_table;
	protected $pbwow_chars_table;
	protected $pbwow_config;

	protected $ranks;
	protected $avatars_enabled;
	protected $avatars_enabled_full;
	protected $tp_ext_enabled;

	/**
	 * pbwowstyle constructor.
	 *
	 * @param \phpbb\config\config              $config
	 * @param \phpbb\cache\service              $cache
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\db\tools\tools             $db_tools
	 * @param \phpbb\event\dispatcher_interface $dispatcher
	 * @param \phpbb\extension\manager          $extension_manager
	 * @param \phpbb\template\template          $template
	 * @param \phpbb\user                       $user
	 * @param \phpbb\path_helper                $path_helper
	 * @param                                   $root_path
	 * @param                                   $phpEx
	 * @param                                   $pbwow_config_table
	 */
	public function __construct(config $config,
	                            service $cache,
	                            driver_interface $db,
	                            \phpbb\db\tools\tools $db_tools,
	                            dispatcher_interface $dispatcher,
	                            \phpbb\extension\manager $extension_manager,
	                            \phpbb\template\template $template,
	                            \phpbb\user $user,
	                            \phpbb\path_helper $path_helper,
	                            $root_path,
	                            $phpEx,
	                            $pbwow_config_table)
	{
		$this->config = $config;
		$this->cache = $cache;
		$this->db = $db;
		$this->db_tools = $db_tools;
		$this->dispatcher = $dispatcher;
		$this->extension_manager = $extension_manager;
		$this->template = $template;
		$this->user = $user;
		$this->path_helper = $path_helper;
		$this->root_path = $root_path;
		$this->phpEx = $phpEx;
		$this->pbwow_config_table = $pbwow_config_table;
		$this->get_pbwow_config();
	}

	/**
	 * Assign global template vars, based on the ACP config of the extension
	 */
	public function global_style_append()
	{
		$pbwow_config = $this->pbwow_config;

		if (isset($pbwow_config) && is_array($pbwow_config))
		{
			extract($pbwow_config);
		}
		else
		{
			return;
		}
		$logo_margins='';
		$tpl_vars = array();
		$body_class = ' pbwow-ext';

		// Logo
		if ($logo_enable && isset($logo_src) && isset($logo_size_width) && isset($logo_size_height) && $logo_size_width > 1 && $logo_size_height > 1)
		{
			$tpl_vars += array(
				'S_PBLOGO'          => true,
				'PBLOGO_SRC'        => $this->path_helper->update_web_root_path($this->root_path . html_entity_decode($logo_src)),
				'PBLOGO_WIDTH'      => $logo_size_width,
				'PBLOGO_HEIGHT'     => $logo_size_height,
				'PBLOGO_WIDTH_MOB'  => floor(($logo_size_width * 0.8)),
				'PBLOGO_HEIGHT_MOB' => floor(($logo_size_height * 0.8)),
				'PBLOGO_MARGINS'    => $logo_margins,
			);

			if (isset($logo_margins) && strlen($logo_margins) > 0)
			{
				$tpl_vars += array(
					'PBLOGO_MARGINS' => $logo_margins,
				);
			}
		}

		// Top-bar
		if ($topbar_enable && isset($topbar_code))
		{
			$tpl_vars += array(
				'TOPBAR_CODE' => str_replace('&', '&amp;', html_entity_decode($topbar_code)),
			);
			$body_class .= ' topbar';

			if ($topbar_fixed)
			{
				$tpl_vars += array(
					'S_TOPBAR_FIXED' => true,
				);
				$body_class .= ' topbar-fixed';
			}
		}

		// Video BG
		if ($videobg_enable)
		{
			$tpl_vars += array(
				'S_VIDEOBG' => true,
			);
			$body_class .= ' videobg';

			if ($videobg_allpages)
			{
				$tpl_vars += array(
					'S_VIDEOBG_ALL' => true,
				);
				$body_class .= ' videobg-all';
			}
		}

		// Fixed BG
		if ($fixedbg)
		{
			$tpl_vars += array(
				'S_FIXEDBG' => true,
			);
			$body_class .= ' fixedbg';

			if ($topbar_enable && !$topbar_fixed)
			{
				// if we don't do this, scrolling down will look weird
				$body_class .= ' topbar-fixed';
			}
		}

		// Misc
		$tpl_vars += array(
			'HEADERLINKS_CODE' 	=> ($headerlinks_enable && isset($headerlinks_code)) ? str_replace('&', '&amp;', html_entity_decode($headerlinks_code)) : false,
			'ADS_INDEX_CODE' 	=> ($ads_index_enable && isset($ads_index_code)) ? str_replace('&', '&amp;', html_entity_decode($ads_index_code)) : false,
			'S_PBWOW_AVATARS'	=> isset($avatars_enable) ? $avatars_enable : false,
			'S_SMALL_RANKS' 	=> (isset($smallranks_enable) && $this->avatars_enabled) ? $smallranks_enable : false,
		);

		// Assign vars
		$this->template->assign_vars($tpl_vars);
		$this->template->append_var('BODY_CLASS', $body_class);
	}


	/**
	 * Gets the PBWoW config data from the DB, or the cache if it is present
	 */
	protected function get_pbwow_config()
	{
		if (($this->pbwow_config = $this->cache->get('pbwow_config')) != true)
		{
			$this->pbwow_config = array();

			if ($this->db_tools->sql_table_exists($this->pbwow_config_table))
			{
				$sql = 'SELECT config_name, config_value FROM ' . $this->pbwow_config_table;
				$result = $this->db->sql_query($sql);

				while ($row = $this->db->sql_fetchrow($result))
				{
					$this->pbwow_config[$row['config_name']] = $row['config_value'];
				}
				$this->db->sql_freeresult($result);

			}
			$this->cache->put('pbwow_config', $this->pbwow_config);
		}
	}


}

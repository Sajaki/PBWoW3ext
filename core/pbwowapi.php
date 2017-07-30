<?php
/**
 *
 * @package PBWoW Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwow\core;

use phpbb\cache\service;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\db\tools;
use phpbb\event\dispatcher_interface;

class pbwowapi
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
	 * pbwow constructor.
	 *
	 * @param \phpbb\config\config              $config
	 * @param \phpbb\cache\service              $cache
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\db\tools\tools             $db_tools
	 * @param \phpbb\event\dispatcher_interface $dispatcher
	 * @param \phpbb\extension\manager          $extension_manager
	 * @param \phpbb\profilefields\manager      $profilefields_manager
	 * @param \phpbb\template\template          $template
	 * @param \phpbb\user                       $user
	 * @param \phpbb\path_helper                $path_helper
	 * @param                                   $root_path
	 * @param                                   $phpEx
	 * @param                                   $pbwow_config_table
	 * @param                                   $pbwow_chars_table
	 */
	public function __construct(config $config, service $cache, driver_interface $db, \phpbb\db\tools\tools $db_tools, dispatcher_interface $dispatcher, \phpbb\extension\manager $extension_manager, \phpbb\profilefields\manager $profilefields_manager, \phpbb\template\template $template, \phpbb\user $user, \phpbb\path_helper $path_helper, $root_path, $phpEx, $pbwow_config_table, $pbwow_chars_table)
	{
		$this->config = $config;
		$this->cache = $cache;
		$this->db = $db;
		$this->db_tools = $db_tools;
		$this->dispatcher = $dispatcher;
		$this->extension_manager = $extension_manager;
		$this->profilefields_manager = $profilefields_manager;
		$this->template = $template;
		$this->user = $user;
		$this->path_helper = $path_helper;
		$this->root_path = $root_path;
		$this->phpEx = $phpEx;

		$this->pbwow_config_table = $pbwow_config_table;
		$this->pbwow_chars_table = $pbwow_chars_table;
		$this->get_pbwow_config();

		$this->avatars_enabled = ($config['allow_avatar'] && $user->optionget('viewavatars')) ? true : false;
		$this->avatars_enabled_full = ($this->avatars_enabled && $this->pbwow_config['avatars_enable']) ? true : false;
		$this->tp_ext_enabled = $extension_manager->is_enabled('vse/topicpreview');
	}

	/**
	 * Generate the PBWoW avatar for the current user (for display in the header)
	 * handler for core.page_header_after
	 */
	public function global_style_append_after()
	{
		if ($this->avatars_enabled_full && $this->config['load_cpf_viewtopic'] && $this->user->data['is_registered'])
		{
			$user_data = $this->user->data;
			$user_id = $user_data['user_id'];

			if (isset($user_data['user_avatar']) && empty($user_data['user_avatar']))
			{
				$cp = $this->profilefields_manager->grab_profile_fields_data($user_id);

				if (!empty($cp))
				{
					$pf = $this->profilefields_manager->generate_profile_fields_template_data($cp[$user_id]);

					if (isset($pf['row']['PROFILE_PBAVATAR']) && !empty($pf['row']['PROFILE_PBAVATAR']))
					{
						$this->template->assign_var('CURRENT_USER_AVATAR', $pf['row']['PROFILE_PBAVATAR']);
					}
				}
			}
		}
	}

	/**
	 * Processes the users's profile-field data as soon as it is grabbed from the DB.
	 * It will use the profile-field data to try to grab info from the Battle.net API.
	 *
	 * @var    int|array $user_ids   Single user id or an array of ids
	 * @var    array     $field_data Array with profile fields data
	 *
	 * @return array     $field_data Array with modified profile fields data
	 */
	public function process_pf_grab($user_ids, $field_data)
	{
		$pbwow_config = $this->pbwow_config;

		if (isset($pbwow_config['bnetchars_enable']) && $pbwow_config['bnetchars_enable'] && $this->avatars_enabled_full)
		{
			$cachelife  = isset($pbwow_config['bnetchars_cachetime']) ? intval($pbwow_config['bnetchars_cachetime']) : 86400;
			$apitimeout = isset($pbwow_config['bnetchars_timeout']) ? intval($pbwow_config['bnetchars_timeout']) : 1;
			$apikey     = isset($pbwow_config['bnet_apikey']) ? $pbwow_config['bnet_apikey'] : false;

			// No API key? Cancel everything
			if (!$apikey)
			{
				return $field_data;
			}

			// Get all the characters of the requested users
			$sql    = 'SELECT *
				FROM ' . $this->pbwow_chars_table . '
				WHERE ' . $this->db->sql_in_set('user_id', $user_ids);
			$result = $this->db->sql_query($sql);

			$char_data = $no_call_list = array();
			while ($row = $this->db->sql_fetchrow($result))
			{
				$char_data[$row['user_id']] = $row;
			}
			$this->db->sql_freeresult($result);

			// Get a user list with all the API calls to be made
			$call_list = $this->generate_api_call_list($user_ids, $field_data, $char_data, $cachelife, $apikey);

			// Extract the users that have valid CPF input values, but don't need an API call
			if (isset($call_list['no_call']))
			{
				$no_call_list = $call_list['no_call'];
				unset($call_list['no_call']);
			}

			// Get the character data from the Battle.net API
			$api_data = $this->call_bnet_api($call_list, $apitimeout);

			// Use the data from the API to save and merge with CPF data
			$field_data = $this->process_api_data($api_data, $no_call_list, $char_data, $field_data);
		}

		return $field_data;
	}

	protected function generate_api_call_list($user_ids, $field_data, $char_data, $cachelife, $apikey)
	{
		$call_list = array();

		// For each requested user, we will do some magic
		foreach ($user_ids as $user_id)
		{
			$bnet_h = (isset($field_data[$user_id]['pf_pb_bnet_host'])) ? $field_data[$user_id]['pf_pb_bnet_host'] - 1 : 0; // 1 == none, so -1 for all
			$bnet_r = (isset($field_data[$user_id]['pf_pb_bnet_realm'])) ? $field_data[$user_id]['pf_pb_bnet_realm'] : '';
			$bnet_n = (isset($field_data[$user_id]['pf_pb_bnet_name'])) ? $field_data[$user_id]['pf_pb_bnet_name'] : '';

			if ($bnet_h && $bnet_r && $bnet_n)
			{
				$callAPI = false;

				// Determine if the API should be called, based on cache TTL, # of tries, and character change
				if (isset($char_data[$user_id]))
				{
					$age = time() - $char_data[$user_id]['updated'];

					switch ($char_data[$user_id]["tries"])
					{
						case 0:
							break;
						case 1:
							$callAPI = ($age > 60) ? true : false;
							break;
						case 2:
							$callAPI = ($age > 600) ? true : false;
							break;
						case 3:
							$callAPI = ($age > ($cachelife / 24)) ? true : false;
							break;
						case 4:
							$callAPI = ($age > ($cachelife / 4)) ? true : false;
							break;
						default:
							break; // More than 4 tries > just wait for TTL
					}

					// Maximum cache time exceeded > call the API
					if ($age > $cachelife)
					{
						$callAPI = true;
					}

					// Changed character name?
					if ($bnet_n !== $char_data[$user_id]['name'])
					{
						$callAPI = true;
					}
				}
				else
				{
					$callAPI = true;
				}

				if ($callAPI == true)
				{
					//http://us.battle.net/api
					// CPF values haven't been assigned yet, so have to do it manually
					switch ($bnet_h)
					{
						case 1:
							$bnet_h   = "us.api.battle.net";
							$bnet_loc = "us.battle.net";
							$loc      = "us";
							break;
						case 2:
							$bnet_h   = "eu.api.battle.net";
							$bnet_loc = "eu.battle.net";
							$loc      = "eu";
							break;
						case 3:
							$bnet_h   = "kr.api.battle.net";
							$bnet_loc = "kr.battle.net";
							$loc      = "kr";
							break;
						case 4:
							$bnet_h   = "tw.api.battle.net";
							$bnet_loc = "tw.battle.net";
							$loc      = "tw";
							break;
						case 5:
							$bnet_h   = "www.battlenet.com.cn";
							$bnet_loc = "www.battlenet.com.cn";
							$loc      = "cn";
							break;
						default:
							$bnet_h   = "us.api.battle.net";
							$bnet_loc = "us.battle.net";
							$loc      = "us";
							break;
					}

					// Sanitize
					$bnet_r = str_replace("'", "", strtolower(trim($bnet_r)));
					$bnet_r = str_replace(" ", "-", $bnet_r);
					$bnet_r = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($bnet_r, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8');
					$bnet_n = str_replace(" ", "-", $bnet_n);

					// Get API data
					$call_list[$user_id] = array(
						'api_url'  => "https://" . $bnet_h . "/wow/character/" . $bnet_r . "/" . $bnet_n . "?fields=guild&apikey=" . $apikey,
						'bnet_h'   => $bnet_h,
						'bnet_r'   => $bnet_r,
						'bnet_n'   => $bnet_n,
						'bnet_loc' => $bnet_loc,
						'loc'      => $loc,
					);
				}
				else
				{
					$call_list['no_call'][] = $user_id;
				}
			}
		}

		return $call_list;
	}

	/**
	 * Use cURL to get data from remote servers (such as Battle.net avatars)
	 */
	protected function call_bnet_api($call_list, $timeout = 1)
	{
		// TODO: group API requests by $bnet_h, and open a socket for each to get them all in 1 go
		foreach ($call_list as $user_id => $call_data)
		{
			$ch = curl_init();
			// TODO: make this asynchronous somehow, so the page doesn't have to wait for Battle.net
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $call_data['api_url']);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec($ch);
			curl_close($ch);

			$response_data = array();
			$error = false;

			// Determine error type and decode response
			if ($response === false)
			{
				$error = 'Battle.net connection problem';
			}
			else
			{
				$response_data = json_decode($response, true);

				if (isset($response_data['code']))
				{
					if (is_int($response_data['code']))
					{
						$error = $response_data['code'] . ' - ' . $response_data['type'];
					}
					else
					{
						$error = $response_data['detail'];
					}
				}
				else if (isset($response_data['status']))
				{
					$error = $response_data['reason'];
				}
				else if (!isset($response_data['name']))
				{
					$error = 'Unknown API error';
				}
			}

			$call_list[$user_id]['data'] = $response_data;
			$call_list[$user_id]['error'] = $error;
		}

		return $call_list;
	}

	/**
	 * @param $api_data
	 * @param $no_call_list
	 * @param $char_data
	 * @param $field_data
	 * @return mixed
	 */
	protected function process_api_data($api_data, $no_call_list, $char_data, $field_data)
	{
		foreach ($api_data as $user_id => $data_array)
		{
			// If the API data cannot be retrieved, register the number of tries to prevent flooding
			if ($data_array['error'])
			{
				// Character already exists in the DB
				if (isset($char_data[$user_id]))
				{
					$tries = ($char_data[$user_id]['tries'] < 100) ? $char_data[$user_id]['tries'] + 1 : 100; // Prevent int 128 out of range

					$sql_ary = array(
						'user_id' => $user_id,
						'updated' => time(),
						'tries'   => $tries,
						'name'    => $data_array['bnet_n'],
						'realm'   => $data_array['bnet_r'],
						'url'     => $data_array['error'],
					);

					$sql = 'UPDATE ' . $this->pbwow_chars_table . '
						SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE user_id = ' . $user_id;
					$this->db->sql_query($sql);
				}
				// Not yet in DB, add a new entry
				else
				{
					$sql_ary = array(
						'user_id' => $user_id,
						'updated' => time(),
						'tries'   => 1,
						'name'    => $data_array['bnet_n'],
						'realm'   => $data_array['bnet_r'],
						'url'     => $data_array['error'],
					);

					$sql = 'INSERT INTO ' . $this->pbwow_chars_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
					$this->db->sql_query($sql);
				}

				$field_data[$user_id]['pf_pb_bnet_url'] = $data_array['error'];
			}
			else
			{
				$data = $data_array['data'];

				// Set avatar path
				$avatar    = (!empty($data['thumbnail'])) ? $data['thumbnail'] : '';
				$avatarURL = '';
				if ($avatar)
				{
					// https://us.battle.net/forums/en/wow/topic/20752046076
					// old
					// lightbringer/144/29142672-avatar.jpg
					// new
					// http://render-eu.worldofwarcraft.com/character/lightbringer/144/29142672-avatar.jpg
					$avatarURL = $this->config['server_protocol'] . 'render-' . $data_array['loc'] . ".worldofwarcraft.com/" . 'character/' . $avatar;

					//$avatarIMG = @file_get_contents($IMGURL); // TODO cache them locally
				}

				// Conform Blizzard's race ID numbers to PBWoW, so the CPF will work correctly
				$data_race = $data['race'];
				switch ($data_race)
				{
					case 22:
						$data_race = 12;
						break;
					case 24:
					case 25:
					case 26:
						$data_race = 13;
						break;
					//default: $data_race; break;
				}
				$data_race += 1;
				$data_class  = $data['class'] + 1;
				$data_gender = $data['gender'] + 2;
				$data_level  = $data['level'];
				$data_guild  = (isset($data['guild']) && is_array($data['guild'])) ? $data['guild']['name'] : "";
				$bnetURL     = $this->config['server_protocol'] . $data_array['bnet_loc'] . "/wow/character/" . $data_array['bnet_r'] . "/" . $data_array['bnet_n'] . "/";

				// Insert into character DB table
				$sql_ary = array(
					'user_id'            => $user_id,
					'updated'            => time(),
					'tries'              => 0,
					'game'               => "wow",
					'last_modified'      => $data['lastModified'],
					'name'               => $data['name'],
					'realm'              => $data['realm'],
					'battlegroup'        => $data['battlegroup'],
					'class'              => $data_class,
					'race'               => $data_race,
					'gender'             => $data_gender,
					'level'              => $data_level,
					'achievement_points' => $data['achievementPoints'],
					'url'                => $bnetURL,
					'avatar'             => $avatar,
					'avatar_url'         => $avatarURL,
					'calc_class'         => $data['calcClass'],
					'total_hk'           => $data['totalHonorableKills'],
					'guild'              => $data_guild,
				);

				if (isset($char_data[$user_id]))
				{
					$sql = 'UPDATE ' . $this->pbwow_chars_table . '
						SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE user_id = ' . $user_id;
				}
				else
				{
					$sql = 'INSERT INTO ' . $this->pbwow_chars_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
				}
				$this->db->sql_query($sql);

				// Merge with rest of CPF values
				$field_data[$user_id]['pf_pb_wow_guild']   = $data_guild;
				//$field_data[$user_id]['pf_pb_wow_pbrealm'] = $data['realm'];
				$field_data[$user_id]['pf_pb_wow_class']   = $data_class;
				$field_data[$user_id]['pf_pb_wow_race']    = $data_race;
				$field_data[$user_id]['pf_pb_wow_gender']  = $data_gender;
				$field_data[$user_id]['pf_pb_wow_level']   = $data_level;
				$field_data[$user_id]['pf_pb_bnet_url']    = $bnetURL;
				$field_data[$user_id]['pf_pb_bnet_avatar'] = $avatarURL;
			}
		}

		foreach ($no_call_list as $user_id)
		{
			if ($char_data[$user_id]['avatar_url']) // No API call needed, just use the current data
			{
				// Merge with rest of CPF values
				$field_data[$user_id]['pf_pb_wow_guild'] = $char_data[$user_id]['guild'];
				//$field_data[$user_id]['pf_pb_wow_realm'] = $char_data[$user_id]['realm'];
				$field_data[$user_id]['pf_pb_wow_class'] = $char_data[$user_id]['class'];
				$field_data[$user_id]['pf_pb_wow_race'] = $char_data[$user_id]['race'];
				$field_data[$user_id]['pf_pb_wow_gender'] = $char_data[$user_id]['gender'];
				$field_data[$user_id]['pf_pb_wow_level'] = $char_data[$user_id]['level'];
				$field_data[$user_id]['pf_pb_bnet_url'] = $char_data[$user_id]['url'];
				$field_data[$user_id]['pf_pb_bnet_avatar'] = $char_data[$user_id]['avatar_url'];
			}
			else // No API call, but also no current (complete) data
			{
				$field_data[$user_id]['pf_pb_wow_guild'] = $char_data[$user_id]['guild'];
				$field_data[$user_id]['pf_pb_bnet_url'] = $char_data[$user_id]['url'];
			}
		}

		return $field_data;
	}

	/**
	 * Processes the users's profile-field data and generates an avatar (when available).
	 * It also tries to determine the faction of a gaming character, based on the data.
	 *
	 * @var     array  profile_row  Array with users profile field data
	 * @var     array  tpl_fields   Array with template data fields
	 *
	 * @return  array  $tpl_fields  Array with modified template data fields
	 */
	public function process_pf_show($profile_row, $tpl_fields)
	{
		$avatars_path = !empty($this->pbwow_config['avatars_path']) ? $this->root_path . $this->pbwow_config['avatars_path'] . '/' : false;

		if (empty($profile_row))
		{
			return $tpl_fields;
		}

		if ($this->avatars_enabled_full && $avatars_path)
		{
			$avatar = '';
			$faction = 0;
			$width = $height = 64;
			// A listener can set this variable to `true` when it overrides this function
			$function_override = false;

			/**
			 * Event to modify the profile field processing script before the supported games are processed
			 *
			 * @event paybas.pbwow.modify_process_pf_before
			 * @var    array   profile_row       Array with users profile field data
			 * @var    array   tpl_fields        Array with template data fields
			 * @var    string  avatars_path      The path to the dir containing the game-avatars
			 * @var    string  avatar            Filename of the avatar img
			 * @var    int     width             The width of the avatar img (in pixels)
			 * @var    int     height            The height of the avatar img (in pixels)
			 * @var    int     faction           The faction of the character
			 * @var    bool    function_override Return the results right after this, or continue?
			 * @since 3.0.0
			 */
			$vars = array(
				'profile_row',
				'tpl_fields',
				'avatars_path',
				'avatar',
				'width',
				'height',
				'faction',
				'function_override',
			);
			extract($this->dispatcher->trigger_event('paybas.pbwow.modify_process_pf_before', compact($vars)));

			if ($function_override)
			{
				return $tpl_fields;
			}

			$wow_r = isset($tpl_fields['row']['PROFILE_PB_WOW_RACE_VALUE_RAW']) ? $profile_row['pb_wow_race']['value'] - 1 : null; // Get the WoW race ID
			$wow_c = isset($tpl_fields['row']['PROFILE_PB_WOW_CLASS_VALUE_RAW']) ? $profile_row['pb_wow_class']['value'] - 1 : null; // Get the WoW class ID
			$wow_g = isset($tpl_fields['row']['PROFILE_PB_WOW_GENDER_VALUE_RAW']) ? $profile_row['pb_wow_gender']['value'] - 1 : null; // Get the WoW gender ID
			$wow_l = isset($tpl_fields['row']['PROFILE_PB_WOW_LEVEL_VALUE_RAW']) ? $profile_row['pb_wow_level']['value'] - 1 : null; // Get the WoW level
			$d3_c = isset($tpl_fields['row']['PROFILE_PB_DIABLO_CLASS_VALUE_RAW']) ? $profile_row['pb_diablo_class']['value'] - 1 : null; // Get the Diablo class ID
			$d3_f = isset($tpl_fields['row']['PROFILE_PB_DIABLO_FOLLOWER_VALUE_RAW']) ? $profile_row['pb_diablo_follower']['value'] - 1 : null; // Get the Diablo class ID
			$d3_g = isset($tpl_fields['row']['PROFILE_PB_DIABLO_GENDER_VALUE_RAW']) ? $profile_row['pb_diablo_gender']['value'] - 1 : null; // Get the Diablo gender ID
			$ws_r = isset($tpl_fields['row']['PROFILE_PB_WILDSTAR_RACE_VALUE_RAW']) ? $profile_row['pb_wildstar_race']['value'] - 1 : null; // Get the Wildstar race ID
			$ws_c = isset($tpl_fields['row']['PROFILE_PB_WILDSTAR_CLASS_VALUE_RAW']) ? $profile_row['pb_wildstar_class']['value'] - 1 : null; // Get the Wildstar class ID
			$ws_g = isset($tpl_fields['row']['PROFILE_PB_WILDSTAR_GENDER_VALUE_RAW']) ? $profile_row['pb_wildstar_gender']['value'] - 1 : null; // Get the Wildstar gender ID
			$ws_p = isset($tpl_fields['row']['PROFILE_PB_WILDSTAR_PATH_VALUE_RAW']) ? $profile_row['pb_wildstar_path']['value'] - 1 : null; // Get the Wildstar path ID
			//$bnet_h = (isset($tpl_fields['row']['PROFILE_PB_BNET_HOST_VALUE'])) ? $tpl_fields['row']['PROFILE_PB_BNET_HOST_VALUE'] : NULL; // Get the Battle.net host
			//$bnet_r = (isset($tpl_fields['row']['PROFILE_PB_BNET_REALM_VALUE'])) ? $tpl_fields['row']['PROFILE_PB_BNET_REALM_VALUE'] : NULL; // Get the Battle.net realm
			//$bnet_n = (isset($tpl_fields['row']['PROFILE_PB_BNET_NAME_VALUE'])) ? $tpl_fields['row']['PROFILE_PB_BNET_NAME_VALUE'] : NULL; // Get the Battle.net character name
			//$bnet_u = isset($tpl_fields['row']['PROFILE_PB_BNET_URL_VALUE']) ? $profile_row['pb_bnet_url']['value'] : null; // Get the Battle.net avatar
			$bnet_a = isset($tpl_fields['row']['PROFILE_PB_BNET_AVATAR_VALUE']) ? $profile_row['pb_bnet_avatar']['value'] : null; // Get the Battle.net avatar

			// I know it looks silly, but we need this to fix icon classes in templates
			if ($wow_r > 0)
			{
				$tpl_fields['row']['PROFILE_PB_WOW_RACE_VALUE_RAW'] = $wow_r;
			}
			if ($wow_c > 0)
			{
				$tpl_fields['row']['PROFILE_PB_WOW_CLASS_VALUE_RAW'] = $wow_c;
			}
			if ($wow_g > 0)
			{
				$tpl_fields['row']['PROFILE_PB_WOW_GENDER_VALUE_RAW'] = $wow_g;
			}
			if ($d3_c > 0)
			{
				$tpl_fields['row']['PROFILE_PB_DIABLO_CLASS_VALUE_RAW'] = $d3_c;
			}
			if ($d3_f > 0)
			{
				$tpl_fields['row']['PROFILE_PB_DIABLO_FOLLOWER_VALUE_RAW'] = $d3_f;
			}
			if ($d3_g > 0)
			{
				$tpl_fields['row']['PROFILE_PB_DIABLO_GENDER_VALUE_RAW'] = $d3_g;
			}
			if ($ws_r > 0)
			{
				$tpl_fields['row']['PROFILE_PB_WILDSTAR_RACE_VALUE_RAW'] = $ws_r;
			}
			if ($ws_c > 0)
			{
				$tpl_fields['row']['PROFILE_PB_WILDSTAR_CLASS_VALUE_RAW'] = $ws_c;
			}
			if ($ws_g > 0)
			{
				$tpl_fields['row']['PROFILE_PB_WILDSTAR_GENDER_VALUE_RAW'] = $ws_g;
			}
			if ($ws_p > 0)
			{
				$tpl_fields['row']['PROFILE_PB_WILDSTAR_PATH_VALUE_RAW'] = $ws_p;
			}

			$valid = false; // determines whether a specific profile field combination is valid (for the game)
			$avail = false; // determines whether an avatar image is available for the profile field combination

			/* Battle.net API */
			//if ($bneth !== NULL && $bnet_r !== NULL && $bnet_n !== NULL && $user_id !== 0 && $bnet_a !== NULL) {
			if ($bnet_a !== null)
			{
				if (isset($wow_r))
				{
					$faction = (in_array($wow_r, array(1, 3, 4, 7, 11, 12))) ? 1 : 2;
				}

				$width = 84;
				$height = 84;

				$avatars_path = '';
				$avatar = $bnet_a;
			}

			/* WoW without Battle.net */
			else if ($wow_r !== null)
			{
				/* Remapping options */
				// $R = $wow_r;
				// $wow_r = ($R == 1) ? 4 : $wow_r; // first item in CPF (with "none" = 0), map to race 4 (Night Elf)
				// $wow_r = ($R == 2) ? 9 : $wow_r; // second item in CPF, map to race 9 (Goblin)
				// $wow_r = ($R == 3) ? 12 : $wow_r; // third item in CPF, map to race 12 (Worgen)
				// $wow_r = ($R == 4) ? 2 : $wow_r; // fourth item in CPF, map to race 2 (Orc)
				// etc. etc.

				// $C = $wow_c;
				// $wow_c = ($C == 1) ? 1 : $wow_c; // first item in CPF (with "none" = 0), map to class 1 (Warrior)
				// $wow_c = ($C == 2) ? 4 : $wow_c; // second item in CPF, map to class 4 (Rogue)
				// $wow_c = ($C == 3) ? 6 : $wow_c; // third item in CPF, map to class 6 (Death Knight)
				// etc. etc.

				/* For reference
				wow_r = 1 > Human
				wow_r = 2 > Orc
				wow_r = 3 > Dwarf
				wow_r = 4 > Night Elf
				wow_r = 5 > Undead
				wow_r = 6 > Tauren
				wow_r = 7 > Gnome
				wow_r = 8 > Troll
				wow_r = 9 > Goblin
				wow_r = 10 > Blood Elf
				wow_r = 11 > Draenei
				wow_r = 12 > Worgen
				wow_r = 13 > Pandaren

				wow_c = 1 > Warrior
				wow_c = 2 > Paladin
				wow_c = 3 > Hunter
				wow_c = 4 > Rogue
				wow_c = 5 > Priest
				wow_c = 6 > Death Knight
				wow_c = 7 > Shaman
				wow_c = 8 > Mage
				wow_c = 9 > Warlock
				wow_c = 10 > Monk
				wow_c = 11 > Druid
				wow_c = 12 > Demon Hunter
				*/

				$faction = 3; // Set faction to neutral, until we can determine correct faction
				switch ($wow_r)
				{
					case 1: // Human
						$valid = (in_array($wow_c, array(1, 2, 3, 4, 5, 6, 8, 9, 10))) ? true : false;
						$avail = (in_array($wow_c, array(1, 2, 4, 5, 6, 8, 9))) ? true : false;
						$faction = 1;
						break;

					case 2: // Orc
						$valid = (in_array($wow_c, array(1, 3, 4, 6, 7, 8, 9, 10))) ? true : false;
						$avail = (in_array($wow_c, array(1, 3, 4, 6, 7, 9))) ? true : false;
						$faction = 2;
						break;

					case 3: // Dwarf
						$valid = (in_array($wow_c, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10))) ? true : false;
						$avail = (in_array($wow_c, array(1, 2, 3, 4, 5, 6))) ? true : false;
						$faction = 1;
						break;

					case 4: // Night Elf
						$valid = (in_array($wow_c, array(1, 3, 4, 5, 6, 8, 10, 11, 12))) ? true : false;
						$avail = (in_array($wow_c, array(1, 3, 4, 5, 6, 11, 12))) ? true : false;
						$faction = 1;
						break;

					case 5: // Undead
						$valid = (in_array($wow_c, array(1, 3, 4, 5, 6, 8, 9, 10))) ? true : false;
						$avail = (in_array($wow_c, array(1, 4, 5, 6, 8, 9))) ? true : false;
						$faction = 2;
						break;

					case 6: // Tauren
						$valid = (in_array($wow_c, array(1, 2, 3, 5, 6, 7, 10, 11))) ? true : false;
						$avail = (in_array($wow_c, array(1, 3, 6, 7, 11))) ? true : false;
						$faction = 2;
						break;

					case 7: // Gnome
						$valid = (in_array($wow_c, array(1, 4, 5, 6, 8, 9, 10))) ? true : false;
						$avail = (in_array($wow_c, array(1, 4, 6, 8, 9))) ? true : false;
						$faction = 1;
						break;

					case 8: // Troll
						$valid = (in_array($wow_c, array(1, 3, 4, 5, 6, 7, 8, 9, 10, 11))) ? true : false;
						$avail = (in_array($wow_c, array(1, 3, 4, 5, 6, 7, 8))) ? true : false;
						$faction = 2;
						break;

					case 9: // Goblin
						$valid = (in_array($wow_c, array(1, 3, 4, 5, 6, 7, 8, 9))) ? true : false;
						//$avail = (in_array($c, array())) ? true : false;
						$faction = 2;
						break;

					case 10: // Blood Elf
						$valid = (in_array($wow_c, array(1, 2, 3, 4, 5, 6, 8, 9, 10, 12))) ? true : false;
						$avail = (in_array($wow_c, array(2, 3, 4, 5, 6, 8, 9, 12))) ? true : false;
						$faction = 2;
						break;

					case 11: // Draenei
						$valid = (in_array($wow_c, array(1, 2, 3, 5, 6, 7, 8, 10))) ? true : false;
						$avail = (in_array($wow_c, array(1, 2, 3, 5, 6, 7, 8))) ? true : false;
						$faction = 1;
						break;

					case 12: // Worgen
						$valid = (in_array($wow_c, array(1, 3, 4, 5, 6, 8, 9, 11))) ? true : false;
						//$avail = (in_array($c, array())) ? true : false;
						$faction = 1;
						break;

					case 13: // Pandaren
						$valid = (in_array($wow_c, array(1, 3, 4, 5, 7, 8, 10))) ? true : false;
						//$avail = (in_array($c, array())) ? true : false;
						$faction = 3;
						break;
				}

				$wow_g = max(0, $wow_g - 1); // 0 = none, 1 = male, 2 = female, but we need a 0/1 map

				if ($valid && $avail)
				{
					if ($wow_l >= 80)
					{
						$path = 'wow/80';
					}
					else if ($wow_l >= 70)
					{
						$path = 'wow/70';
					}
					else if ($wow_l >= 60)
					{
						$path = 'wow/60';
					}
					else
					{
						$path = 'wow/default';
					}

					$avatar = $path . '/' . $wow_g . '-' . $wow_r . '-' . $wow_c . '.gif';
					$width = 64;
					$height = 64;
				}
				else
				{
					$avatar = 'wow/new/' . $wow_r . '-' . $wow_g . '.jpg';
					$width = 84;
					$height = 84;
				}
			}

			/* Diablo */
			else if ($d3_c !== null)
			{
				switch ($d3_c)
				{
					case 1: // Barbarian
						$avatar = 'barbarian';
						break;
					case 2: // Demon Hunter
						$avatar = 'demonhunter';
						break;
					case 3: // Monk
						$avatar = 'monk';
						break;
					case 4: // Witch Doctor
						$avatar = 'witchdoctor';
						break;
					case 5: // Wizard
						$avatar = 'wizard';
						break;
					case 6: // Crusader
						$avatar = 'crusader';
						break;
				}

				$d3_g = (isset($d3_g) && $d3_g > 1) ? 'female' : 'male';
				$avatar = 'diablo/' . $avatar . '_' . $d3_g . '.png';
				$width = 64;
				$height = 64;
				$faction = 3;
			}

			/* Wildstar */
			else if ($ws_r !== null)
			{
				/* For reference
				ws_r = 1 > Human
				ws_r = 2 > Cassian
				ws_r = 3 > Granok
				ws_r = 4 > Draken
				ws_r = 5 > Aurin
				ws_r = 6 > Chua
				ws_r = 7 > Mordesh
				ws_r = 8 > Mechari

				ws_c = 1 > Warrior
				ws_c = 2 > Esper
				ws_c = 3 > Spellslinger
				ws_c = 4 > Stalker
				ws_c = 5 > Medic
				ws_c = 6 > Engineer
				*/

				$faction = 3; // Set faction to neutral, until we can determine correct faction
				switch ($ws_r)
				{
					case 1: // Human
						$valid = $avail = (in_array($ws_c, array(1, 2, 3, 4, 5, 6))) ? true : false;
						$faction = 1;
						break;

					case 2: // Cassian
						$valid = $avail = (in_array($ws_c, array(1, 2, 3, 4, 5, 6))) ? true : false;
						$faction = 2;
						break;

					case 3: // Granok
						$valid = $avail = (in_array($ws_c, array(1, 5, 6))) ? true : false;
						$faction = 1;
						break;

					case 4: // Draken
						$valid = $avail = (in_array($ws_c, array(1, 3, 4))) ? true : false;
						$faction = 2;
						break;

					case 5: // Aurin
						$valid = $avail = (in_array($ws_c, array(2, 3, 4))) ? true : false;
						$faction = 1;
						break;

					case 6: // Chua
						$valid = $avail = (in_array($ws_c, array(2, 3, 5, 6))) ? true : false;
						$faction = 2;
						break;

					case 7: // Mordesh
						$valid = $avail = (in_array($ws_c, array(1, 3, 4, 5, 6))) ? true : false;
						$faction = 1;
						break;

					case 8: // Mechari
						$valid = $avail = (in_array($ws_c, array(1, 4, 5, 6))) ? true : false;
						$faction = 2;
						break;
				}

				$ws_g = max(0, $ws_g - 1); // 0 = none, 1 = male, 2 = female, but we need a 0/1 map

				if ($valid)
				{
					//$avatar = 'wildstar/' . $ws_r . '-' . $wsg . '-' . $ws_c . '.jpg'; // Valid
					$avatar = 'wildstar/' . $ws_r . '.jpg'; // Valid
				}
				else
				{
					$avatar = 'wildstar/' . $ws_r . '.jpg'; // Invalid, show generic race avatar
				}

				$width = 64;
				$height = 64;
			}

			/**
			 * Event to modify the profile field processing script after the supported games are processed
			 *
			 * @event paybas.pbwow.modify_process_pf_after
			 * @var   array   profile_row   Array with users profile field data
			 * @var   array   tpl_fields    Array with template data fields
			 * @var   string  avatars_path  The path to the dir containing the game-avatars
			 * @var   string  avatar        Filename of the avatar img
			 * @var   bool    valid         Whether an PF-value combination is valid (only used in certain cases)
			 * @var   bool    avail         Whether an avatar is available (only used in certain cases)
			 * @var   int     width         The width of the avatar img (in pixels)
			 * @var   int     height        The height of the avatar img (in pixels)
			 * @var   int     faction       The faction of the character
			 * @since 3.0.0
			 */
			$vars = array(
				'profile_row',
				'tpl_fields',
				'avatars_path',
				'avatar',
				'valid',
				'avail',
				'width',
				'height',
				'faction',
			);
			extract($this->dispatcher->trigger_event('paybas.pbwow.modify_process_pf_after', compact($vars)));

			// Add to template fields
			if ($faction || $avatar)
			{
				$tpl_fields['row'] += array(
					'PROFILE_PBFACTION' => ($faction) ? $faction : false,
					'PROFILE_PBAVATAR'  => '<img src="' . $avatars_path . $avatar . '" width="' . $width . '" height="' . $height . '" alt="" />',
				);
			}
		}

		return $tpl_fields;
	}


	/**
	 * These functions allow us to inject the posts-rank and PBWoW avatar into the viewtopic page output
	 */
	public function viewtopic_cache_guest($user_cache_data)
	{
		if ($this->pbwow_config['smallranks_enable'] && $this->avatars_enabled)
		{
			$user_cache_data += array(
				'posts_rank_title'     => '',
				'posts_rank_image'     => '',
				'posts_rank_image_src' => '',
			);
		}

		return $user_cache_data;
	}

	public function viewtopic_cache_user($user_cache_data, $row)
	{
		if ($this->pbwow_config['smallranks_enable'] && $this->avatars_enabled)
		{
			$this->get_user_rank_global(0, $row['user_posts'], $posts_rank_title, $posts_rank_image, $posts_rank_image_src);

			$user_cache_data += array(
				'posts_rank_title'     => isset($posts_rank_title) ? $posts_rank_title : '',
				'posts_rank_image'     => isset($posts_rank_image) ? $posts_rank_image : '',
				'posts_rank_image_src' => isset($posts_rank_image_src) ? $posts_rank_image_src : '',
			);
		}

		return $user_cache_data;
	}

	public function viewtopic_modify_post($user_poster_data, $post_row, $cp_row)
	{
		if ($this->pbwow_config['smallranks_enable'] && $this->avatars_enabled)
		{
			$post_row += array(
				'S_HAS_MULTIPLE_RANKS' => ($user_poster_data['posts_rank_image'] !== $user_poster_data['rank_image']) ? true : false,
				'POSTS_RANK_TITLE'     => $user_poster_data['posts_rank_title'],
				'POSTS_RANK_IMG'       => $user_poster_data['posts_rank_image'],
				'POSTS_RANK_IMG_SRC'   => $user_poster_data['posts_rank_image_src'],
			);
		}

		if ($this->avatars_enabled_full && isset($cp_row['row']['PROFILE_PBAVATAR']))
		{
			$post_row['S_HAS_PBWOW_AVATAR'] = true;

			if (empty($user_poster_data['avatar']))
			{
				$post_row['POSTER_AVATAR'] = $cp_row['row']['PROFILE_PBAVATAR'];
			}
		}

		return $post_row;
	}

	/**
	 * Injects the PBWoW avatar into the view-PM page
	 */
	public function ucp_pm_view_messsage($msg_data, $cp_row)
	{
		if ($this->avatars_enabled_full && isset($cp_row['row']['PROFILE_PBAVATAR']))
		{
			$msg_data['S_HAS_PBWOW_AVATAR'] = true;

			if (empty($msg_data['AUTHOR_AVATAR']))
			{
				$msg_data['AUTHOR_AVATAR'] = $cp_row['row']['PROFILE_PBAVATAR'];
			}
		}

		return $msg_data;
	}

	/**
	 * Injects the PBWoW avatar into the view-profile page data preparation
	 */
	public function memberlist_view_profile($member, $profile_fields)
	{
		if ($this->avatars_enabled_full && isset($profile_fields['row']['PROFILE_PBAVATAR']))
		{
			$member['pbavatar'] = $profile_fields['row']['PROFILE_PBAVATAR'];
		}

		return $member;
	}

	/**
	 * Injects the post-rank and PBWoW avatar into the view-profile page
	 */
	public function memberlist_prepare_profile($data, $template_data)
	{
		if ($this->pbwow_config['smallranks_enable'] && $this->avatars_enabled)
		{
			$this->get_user_rank_global(0, $data['user_posts'], $posts_rank_title, $posts_rank_image, $posts_rank_image_src);

			$template_data += array(
				'S_HAS_MULTIPLE_RANKS' => ($posts_rank_title === $template_data['RANK_TITLE']) ? false : true,
				'POSTS_RANK_TITLE'     => isset($posts_rank_title) ? $posts_rank_title : '',
				'POSTS_RANK_IMG'       => isset($posts_rank_image) ? $posts_rank_image : '',
				'POSTS_RANK_IMG_SRC'   => isset($posts_rank_image_src) ? $posts_rank_image_src : '',
			);
		}

		if ($this->avatars_enabled_full && isset($data['pbavatar']))
		{
			$template_data['S_HAS_PBWOW_AVATAR'] = true;

			if (empty($data['user_avatar']))
			{
				$template_data['AVATAR_IMG'] = $data['pbavatar'];
			}
		}

		return $template_data;
	}


	/**
	 * Modify the topic preview display output, by inserting the custom avatar
	 * if no "normal" avatar is specified (and if a CPF avatar-generated is available).
	 *
	 * @var    array    rowset    Array with topics data (in topic_id => topic_data format)
	 */
	public function topic_preview_modify_row($rowset)
	{
		if (!$this->tp_ext_enabled || !$this->avatars_enabled_full)
		{
			return $rowset;
		}

		$tp_enabled = (!empty($this->config['topic_preview_limit']) && !empty($this->user->data['user_topic_preview'])) ? true : false;
		$tp_avatars = (!empty($this->config['topic_preview_avatars'])) ? true : false;
		$tp_last_post = (!empty($this->config['topic_preview_last_post'])) ? true : false;

		// Only proceed if we want to display avatars and the CPF-generated avatars feature is enabled
		if ($tp_enabled && $tp_avatars && $this->config['load_cpf_viewtopic'])
		{
			$user_ids = array();

			// Get all the user_ids that we want to query
			foreach ($rowset as $topic)
			{
				if (!$topic['fp_avatar'])
				{
					$user_ids[] = $topic['topic_poster'];
				}
				if ($tp_last_post && !$topic['lp_avatar'])
				{
					$user_ids[] = $topic['topic_last_poster_id'];
				}
			}

			if (empty($user_ids))
			{
				return $rowset;
			}

			$user_ids = array_unique($user_ids);
			$pf_fields = $pf_avatars = array();

			// Get the profile data of the specified users
			$pf_data = $this->profilefields_manager->grab_profile_fields_data($user_ids);

			foreach ($pf_data as $profile_fields)
			{
				foreach ($profile_fields as $profile_field)
				{
					$pf_fields[] = $profile_field;
				}
			}

			if (!empty($pf_data))
			{
				// Process the profile data and get an array of all users that have a CPF-generated avatar
				foreach ($pf_data as $user_id => $pf_row)
				{
					$pf_values = $this->profilefields_manager->generate_profile_fields_template_data($pf_row);

					if (isset($pf_values['row']['PROFILE_PBAVATAR']))
					{
						$pf_avatars[$user_id] = $pf_values['row']['PROFILE_PBAVATAR'];
					}
				}
			}

			// Merge the CPF-generated avatars into the topic rows
			foreach ($rowset as &$topic)
			{
				if (isset($pf_avatars[$topic['topic_poster']]))
				{
					$topic['fp_pbavatar'] = $pf_avatars[$topic['topic_poster']];
				}

				if (isset($pf_avatars[$topic['topic_last_poster_id']]))
				{
					$topic['lp_pbavatar'] = $pf_avatars[$topic['topic_last_poster_id']];
				}
			}
		}

		return $rowset;
	}

	/**
	 * Modify the topic preview display output, by inserting the custom avatar
	 * if no "normal" avatar is specified (and if a PBWoW avatar is available).
	 *
	 * @var array $row        Row data
	 * @var array $block      Template vars array
	 * @var int   $tp_avatars Display avatars setting
	 * @return array
	 */
	public function topic_preview_modify_display($row, $block, $tp_avatars)
	{
		if ($tp_avatars && $this->avatars_enabled_full)
		{
			if (empty($row['fp_avatar']) && isset($row['fp_pbavatar']))
			{
				$block['TOPIC_PREVIEW_FIRST_AVATAR'] = $row['fp_pbavatar'];
			}

			if (empty($row['lp_avatar']) && isset($row['lp_pbavatar']))
			{
				$block['TOPIC_PREVIEW_LAST_AVATAR'] = $row['lp_pbavatar'];
			}
		}

		return $block;
	}


	/**
	 * This is a modified copy of the get_user_rank function, as found in functions_display.php
	 * It has been put here so it can be called from any page, which is needed for some PBWoW
	 * features. It also reduces the risk of undefined function errors.
	 *
	 * @param int    $user_rank     the current stored users rank id
	 * @param int    $user_posts    the users number of posts
	 * @param string &$rank_title   the rank title will be stored here after execution
	 * @param string &$rank_img     the rank image as full img tag is stored here after execution
	 * @param string &$rank_img_src the rank image source is stored here after execution
	 *
	 * Note: since we do not want to break backwards-compatibility, this function will only properly assign ranks to guests if you call it for them with user_posts == false
	 */
	public function get_user_rank_global($user_rank, $user_posts, &$rank_title, &$rank_img, &$rank_img_src)
	{
		$ranks = $this->ranks;

		if (empty($ranks))
		{
			$ranks = $this->ranks = $this->cache->obtain_ranks();
		}

		if (!empty($user_rank))
		{
			$rank_title = (isset($ranks['special'][$user_rank]['rank_title'])) ? $ranks['special'][$user_rank]['rank_title'] : '';
			$rank_img = (!empty($ranks['special'][$user_rank]['rank_image'])) ? '<img src="' . $this->root_path . $this->config['ranks_path'] . '/' . $ranks['special'][$user_rank]['rank_image'] . '" alt="' . $ranks['special'][$user_rank]['rank_title'] . '" title="' . $ranks['special'][$user_rank]['rank_title'] . '" />' : '';
			$rank_img_src = (!empty($ranks['special'][$user_rank]['rank_image'])) ? $this->root_path . $this->config['ranks_path'] . '/' . $ranks['special'][$user_rank]['rank_image'] : '';
		}
		else if ($user_posts !== false)
		{
			if (!empty($ranks['normal']))
			{
				foreach ($ranks['normal'] as $rank)
				{
					if ($user_posts >= $rank['rank_min'])
					{
						$rank_title = $rank['rank_title'];
						$rank_img = (!empty($rank['rank_image'])) ? '<img src="' . $this->root_path . $this->config['ranks_path'] . '/' . $rank['rank_image'] . '" alt="' . $rank['rank_title'] . '" title="' . $rank['rank_title'] . '" />' : '';
						$rank_img_src = (!empty($rank['rank_image'])) ? $this->root_path . $this->config['ranks_path'] . '/' . $rank['rank_image'] : '';
						break;
					}
				}
			}
		}
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

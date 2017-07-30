<?php
/**
 *
 * @package PBWoW Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwow\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class listener
 * @package paybas\pbwow\event
 */
class listener implements EventSubscriberInterface
{
	/**
	 * @var \paybas\pbwow\core\pbwow_style
	 */
	protected $pbwow;

	/**
	 * listener constructor.
	 * @param \paybas\pbwow\core\pbwow $pbwow
	 */
	public function __construct(\paybas\pbwow\core\pbwowstyle $pbwow)
	{
		$this->pbwow = $pbwow;
	}

	/**
	 * Returns an array of event names this subscriber wants to listen to.
	 *
	 * @return array The event names to listen to
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header'                           => 'page_header',
			'core.page_header_after'                     => 'page_header_after',

			'core.grab_profile_fields_data'              => 'profile_fields_grab',
			'core.generate_profile_fields_template_data' => 'profile_fields_show',

			'core.viewtopic_cache_guest_data'            => 'viewtopic_cache_guest',
			'core.viewtopic_cache_user_data'             => 'viewtopic_cache_user',
			'core.viewtopic_modify_post_row'             => 'viewtopic_modify_post',

			'core.ucp_pm_view_messsage'                  => 'ucp_pm_view_message',

			'core.memberlist_view_profile'               => 'memberlist_view_profile',
			'core.memberlist_prepare_profile_data'       => 'memberlist_prepare_profile',

			// Topic Preview
			'core.viewforum_modify_topics_data'          => 'topic_preview_modify_row',
			'core.search_modify_rowset'                  => 'topic_preview_modify_row',
			'paybas.recenttopics.modify_topics_list'     => 'topic_preview_modify_row',
			'vse.topicpreview.display_topic_preview'     => 'topic_preview_modify_display',
		);
	}

	/**
	 * Global append functions
	 */
	public function page_header()
	{
		$this->pbwow->global_style_append();
	}

	public function page_header_after()
	{
		$this->pbwow->global_style_append_after();
	}

	/**
	 * CPF processing functions
	 */
	public function profile_fields_grab($event)
	{
		$event['field_data'] = $this->pbwow->process_pf_grab($event['user_ids'], $event['field_data']);
	}

	public function profile_fields_show($event)
	{
		$event['tpl_fields'] = $this->pbwow->process_pf_show($event['profile_row'], $event['tpl_fields']);
	}

	/**
	 * Simple functions that will inject post-ranks and PBWoW avatars
	 */
	public function viewtopic_cache_guest($event)
	{
		$event['user_cache_data'] = $this->pbwow->viewtopic_cache_guest($event['user_cache_data'], $event['poster_id']);
	}

	public function viewtopic_cache_user($event)
	{
		$event['user_cache_data'] = $this->pbwow->viewtopic_cache_user($event['user_cache_data'], $event['row']);
	}

	public function viewtopic_modify_post($event)
	{
		$event['post_row'] = $this->pbwow->viewtopic_modify_post($event['user_poster_data'], $event['post_row'], $event['cp_row']);
	}

	public function ucp_pm_view_message($event)
	{
		$event['msg_data'] = $this->pbwow->ucp_pm_view_messsage($event['msg_data'], $event['cp_row']);
	}

	public function memberlist_view_profile($event)
	{
		$event['member'] = $this->pbwow->memberlist_view_profile($event['member'], $event['profile_fields']);
	}

	public function memberlist_prepare_profile($event)
	{
		$event['template_data'] = $this->pbwow->memberlist_prepare_profile($event['data'], $event['template_data']);
	}

	/**
	 * VSE's Topic Preview
	 */
	public function topic_preview_modify_row($event)
	{
		if (sizeof($event['rowset']))
		{
			// Don't do the preview stuff for search pages in "view posts" mode.
			if ($event['show_results'] && $event['show_results'] == 'posts')
			{
				return;
			}

			$event['rowset'] = $this->pbwow->topic_preview_modify_row($event['rowset']);
		}
	}

	public function topic_preview_modify_display($event)
	{
		$event['block'] = $this->pbwow->topic_preview_modify_display($event['row'], $event['block'], $event['tp_avatars']);
	}
}

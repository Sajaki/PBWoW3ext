<?php
/**
 *
 * @package PBWoW Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paybas\pbwowext\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class stylelistener
 *
 * @package paybas\pbwowext\event
 */
class stylelistener implements EventSubscriberInterface
{
	/**
	 * @var \paybas\pbwowext\core\pbwowstyle
	 */
	protected $pbwowstyle;

	/**
	 * stylelistener constructor.
	 *
	 * @param \paybas\pbwowext\core\pbwowstyle $pbwow_style
	 */
	public function __construct(\paybas\pbwowext\core\pbwowstyle $pbwowstyle)
	{
		$this->pbwowstyle = $pbwowstyle;
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
		);
	}

	/**
	 * Global append functions
	 */
	public function page_header()
	{
		$this->pbwowstyle->global_style_append();
	}

}

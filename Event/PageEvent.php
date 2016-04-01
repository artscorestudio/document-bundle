<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use ASF\DocumentBundle\Model\Page\PageInterface;

/**
 * Page Event
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PageEvent extends Event
{
	/**
	 * @var PageInterface
	 */
	protected $page;
	
	/**
	 * @param PageInterface $page
	 */
	public function __construct(PageInterface $page)
	{
		$this->page = $page;
	}
	
	/**
	 * @return PageInterface
	 */
	public function getPage()
	{
		return $this->page;
	}
}
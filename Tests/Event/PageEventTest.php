<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Tests\Event;

use ASF\DocumentBundle\Event\PageEvent;

/**
 * Page Event Tests
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PageEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PageEvent
     */
    protected $event;
    
    /**
     * {@inheritDoc}
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
    	$page = $this->getMock('ASF\DocumentBundle\Model\Page\PageInterface');
        $this->event = new PageEvent($page);
    }
    
    /**
     * @covers ASF\DocumentBundle\Event\PageEvent
     * @covers ASF\DocumentBundle\Event\PageEvent::getPage
     */
	public function testGetPageMethod()
	{
	    $this->assertInstanceOf('ASF\DocumentBundle\Model\Page\PageInterface', $this->event->getPage());
	}
}
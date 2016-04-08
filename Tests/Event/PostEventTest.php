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

use ASF\DocumentBundle\Event\PostEvent;

/**
 * Post Event Tests
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PostEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PostEvent
     */
    protected $event;
    
    /**
     * {@inheritDoc}
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
    	$post = $this->getMock('ASF\DocumentBundle\Model\Post\PostInterface');
        $this->event = new PostEvent($post);
    }
    
    /**
     * @covers ASF\DocumentBundle\Event\PostEvent
     * @covers ASF\DocumentBundle\Event\PostEvent::getPost
     */
	public function testGetPostMethod()
	{
	    $this->assertInstanceOf('ASF\DocumentBundle\Model\Post\PostInterface', $this->event->getPost());
	}
}
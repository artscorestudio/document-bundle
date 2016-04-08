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

use ASF\DocumentBundle\Event\DocumentEvents;

/**
 * Document Events Tests
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class DocumentEventsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ASF\DocumentBundle\Event\DocumentEvents
     */
	public function testEventNames()
	{
	    $this->assertEquals('asf_document.page.edit.success', DocumentEvents::PAGE_EDIT_SUCCESS);
	    $this->assertEquals('asf_document.post.edit.success', DocumentEvents::POST_EDIT_SUCCESS);
	}
}
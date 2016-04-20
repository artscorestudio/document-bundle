<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Tests\Utils\Manager;

use ASF\DocumentBundle\Utils\Manager\DefaultManager;
use ASF\DocumentBundle\Model\Document\DocumentModel;

/**
 * Base class for Artscore Studio Framework Entity Managers
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class DefaultManagerTest extends \PHPUnit_Framework_TestCase
{
	const DOCUMENT_CLASS = 'ASF\DocumentBundle\Tests\Utils\Manager\Document';
	
    /**
     * @var \ASF\DocumentBundle\Utils\Manager\DefaultManager
     */
    protected $defaultManager;
    
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $em;
    
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $repository;
    
    /**
     * {@inheritDoc}
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        parent::setUp();
        
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
        	$this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->em = $this->getMock('Doctrine\ORM\EntityManagerInterface');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        
        $this->repository->expects($this->any())
        	->method('getClassName')
        	->will($this->returnValue(static::DOCUMENT_CLASS));
        
        $this->em->expects($this->any())
	        ->method('getRepository')
	        ->will($this->returnValue($this->repository));
        
        $this->em->expects($this->any())
	        ->method('getClassMetadata')
	        ->with($this->equalTo(static::DOCUMENT_CLASS))
	        ->will($this->returnValue($class));
        
        $class->expects($this->any())
	        ->method('getName')
	        ->will($this->returnValue(static::DOCUMENT_CLASS));
        
        $this->defaultManager = $this->createConfigManager($this->em, static::DOCUMENT_CLASS);
    }
    
    /**
     * @covers ASF\DocumentBundle\Utils\Manager\DefaultManager
     */
    public function testDocumentEntityManager()
    {
        $this->assertEquals(static::DOCUMENT_CLASS, $this->defaultManager->getClassName());
    }
    
    /**
     * Get Default Entity Manager
     * 
     * @param \PHPUnit_Framework_MockObject_MockObject $entity_manager
     * @param string                                   $entity_name
     * 
     * @return \ASF\WebsiteBundle\Utils\Manager\DefaultManager
     */
    protected function createConfigManager($entity_manager, $entity_name)
    {
    	return new DefaultManager($entity_manager, $entity_name);
    }
}

/**
 * Document class for tests
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 */
class Document extends DocumentModel {}
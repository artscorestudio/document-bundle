<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Tests\DependencyInjection;

use ASF\DocumentBundle\DependencyInjection\ASFDocumentExtension;

/**
 * Bundle's Extension Test Suites
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class ASFDocumentExtensionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var \ASF\LayoutBundle\DependencyInjection\ASFLayoutExtension
	 */
	private $extension;
	
	/**
	 * {@inheritDoc}
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		parent::setUp();

		$this->extension = new ASFDocumentExtension();
	}
	
	/**
	 * @covers ASF\DocumentBundle\DependencyInjection\ASFDocumentExtension::load
	 */
	public function testLoadExtension()
	{
		$this->extension->load(array(), $this->getContainer());
	}
	
	/**
	 * @covers ASF\DocumentBundle\DependencyInjection\ASFDocumentExtension::prepend
	 */
	public function testPrependExtension()
	{
		$this->extension->prepend($this->getContainer());
	}
	
	/**
	 * @covers ASF\DocumentBundle\DependencyInjection\ASFDocumentExtension::configureTwigBundle
	 */
	public function testConfigureTwigBundle()
	{
		$container = new ContainerBuilder();
		$this->extension->configureTwigBundle($container, array(
			'asf_document' => array('form_theme' => 'ASFDocumentBundle:Form:fields.html.twig') 
		));
	}
}
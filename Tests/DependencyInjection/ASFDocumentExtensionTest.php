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

	/**
	 * Return a mock object of ContainerBuilder
	 *
	 * @return \Symfony\Component\DependencyInjection\ContainerBuilder
	 */
	protected function getContainer($bundles = null, $extensions = null)
	{
		$bag = $this->getMock('Symfony\Component\DependencyInjection\ParameterBag\ParameterBag');
		$bag->method('add');

		$container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
		$container->method('getParameter');
		$container->method('getExtensions');
	
		$container->method('getExtensionConfig')->willReturn(array());
		$container->method('prependExtensionConfig');
		$container->method('setAlias');
		$container->method('getExtension');
			
		$container->method('addResource');
		$container->method('setParameter');
		$container->method('getParameterBag')->willReturn($bag);
		$container->method('setDefinition');
		$container->method('setParameter');
	
		return $container;
	}
	
	/**
	 * Return bundle's default configuration
	 *
	 * @return array
	 */
	protected function getDefaultConfig()
	{
		return array(
			'form_theme' => 'ASFDocumentBundle:Form:fields.html.twig',
			'page' => array(
				'versionable' => true,
				'signable' => true,
				'form' => array(
					'type' => "ASF\DocumentBundle\Form\Type\PageType",
					'name' => 'page_type'
				)
			),
			'post' => array(
				'versionable' => true,
				'signable' => true,
				'form' => array(
					'type' => "ASF\DocumentBundle\Form\Type\PostType",
					'name' => 'post_type'
				)
			)
		);
	}
}
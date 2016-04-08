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

use Symfony\Component\Config\Definition\Processor;
use ASF\DocumentBundle\DependencyInjection\Configuration;

/**
 * This test case check if the default bundle's configuration from bundle's Configuration class is OK
 *  
 * @author Nicolas Claverie <info@artscore-studio.fr
 *
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
	 * @var array
	 */
	private $defaultConfig;
	
	/**
	 * {@inheritDoc}
	 * @see PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp()
	{
		$processor = new Processor();
		$this->defaultConfig = $processor->processConfiguration(new Configuration(), array());
	}
	
    /**
     * @covers ASF\DocumentBundle\DependencyInjection\Configuration
     */
	public function testDefaultConfiguration()
	{
		$this->assertCount(3, $this->defaultConfig);
	}
	
	/**
	 * @covers ASF\DocumentBundle\DependencyInjection\Configuration
	 */
	public function testFormThemeParameter()
	{
		$this->assertEquals('ASFDocumentBundle:Form:fields.html.twig', $this->defaultConfig['form_theme']);
	}
	
	/**
	 * @covers ASF\DocumentBundle\DependencyInjection\Configuration::addPageParameterNode
	 */
	public function testPageLoadFormName()
	{
		$this->assertEquals('ASF\DocumentBundle\Form\Type\PageType', $this->defaultConfig['page']['form']['type']);
		$this->assertEquals('page_type', $this->defaultConfig['page']['form']['name']);
	}
	
	/**
	 * @covers ASF\DocumentBundle\DependencyInjection\Configuration::addPageParameterNode
	 */
	public function testPageVersionableParameter()
	{
		$this->assertFalse($this->defaultConfig['page']['versionable']);
	}
	
	/**
	 * @covers ASF\DocumentBundle\DependencyInjection\Configuration::addPageParameterNode
	 */
	public function testPageSignableParameter()
	{
		$this->assertFalse($this->defaultConfig['page']['signable']);
	}
	
	/**
	 * @covers ASF\DocumentBundle\DependencyInjection\Configuration::addPostParameterNode
	 */
	public function testPostLoadFormName()
	{
		$this->assertEquals('ASF\DocumentBundle\Form\Type\PostType', $this->defaultConfig['post']['form']['type']);
		$this->assertEquals('post_type', $this->defaultConfig['post']['form']['name']);
	}
	
	/**
	 * @covers ASF\DocumentBundle\DependencyInjection\Configuration::addPostParameterNode
	 */
	public function testPostVersionableParameter()
	{
		$this->assertFalse($this->defaultConfig['post']['versionable']);
	}
	
	/**
	 * @covers ASF\DocumentBundle\DependencyInjection\Configuration::addPostParameterNode
	 */
	public function testPostSignableParameter()
	{
		$this->assertFalse($this->defaultConfig['post']['signable']);
	}
}
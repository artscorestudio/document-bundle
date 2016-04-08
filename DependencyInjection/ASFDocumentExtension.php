<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ASFDocumentExtension extends Extension implements PrependExtensionInterface
{
	/**
	 * Maps parameters in container
	 *
	 * @param ContainerBuilder $container
	 * @param string $rootNodeName
	 * @param array $config
	 */
	public function mapsParameters(ContainerBuilder $container, $rootNodeName, $config)
	{
		foreach($config as $name => $value) {
			if ( is_array($value) ) {
				$this->mapsParameters($container, $rootNodeName . '.' . $name, $value);
			} else {
				$container->setParameter($rootNodeName . '.' . $name, $value);
			}
		}
	}
	
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->mapsParameters($container, $this->getAlias(), $config);
        
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/services.xml');
        $loader->load('services/page.xml');
        $loader->load('services/post.xml');
    }
    
    /**
     * {@inheritDoc}
     * @see \Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface::prepend()
     */
    public function prepend(ContainerBuilder $container)
    {
    	$bundles = $container->getParameter('kernel.bundles');
    
    	$configs = $container->getExtensionConfig($this->getAlias());
    	$config = $this->processConfiguration(new Configuration(), $configs);
    
    	$this->configureTwigBundle($container, $config);
    }
    
    /**
     * Configure twig bundle
     *
     * @param ContainerBuilder $container
     * @param array $config
     */
    public function configureTwigBundle(ContainerBuilder $container, array $config)
    {
    	foreach(array_keys($container->getExtensions()) as $name) {
    		switch($name) {
    			case 'twig':
    				$container->prependExtensionConfig($name, array(
    					'form_themes' => array($config['form_theme'])
    				));
    				break;
    		}
    	}
    }
}

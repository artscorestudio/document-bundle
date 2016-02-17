<?php
/**
 * This file is part of Artscore Studio Framework package
 *
 * (c) 2012-2015 Artscore Studio <info@artscore-studio.fr>
 *
 * This source file is subject to the MIT Licence that is bundled
 * with this source code in the file LICENSE.
 */
namespace ASF\DocumentBundle\DependencyInjection;

use ASF\CoreBundle\DependencyInjection\AsfExtension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ASFDocumentExtension extends AsfExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->mapsParameter($container, 'asf_doc', $config);
        
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('services/Document.xml');
        $loader->load('services/Page.xml');
        $loader->load('services/Post.xml');
        
        if ( true === $config['supports']['asf_ui']) {
        	$loader->load('services/asf_ui.xml');
        }
    }

    /**
     * Configure other bundles according to the bundle configuraton
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
    	$bundles = $container->getParameter('kernel.bundles');
    
    	$configs = $container->getExtensionConfig($this->getAlias());
    	$config = $this->processConfiguration(new Configuration(), $configs);
    	
    	$this->configureAsfUIBundle($config, $container);
    	$this->configureAsfUserBundle($config, $container);
    	$this->configureGenemuFormBundle($config, $container);
    }

    /**
     * This method check if genemu/form-bundle is installed and configure it
     *
     * @param array $config
     * @param ContainerBuilder $container
     */
    protected function configureGenemuFormBundle(array $config, ContainerBuilder $container)
    {
    	$found = false;
    	foreach(array_keys($container->getExtensions()) as $name) {
    		switch($name) {
    			case 'genemu_form':
    				$found = true;
    				$container->prependExtensionConfig($name, array(
    					'autocomplete' => true
    				));
    				break;
    		}
    	}
    
    	if ( false === $found && $config['supports']['asf_ui'])
    		throw new \Exception('You must install "artscorestudio/ui-bundle" or disable asf_ui support in bundle\'s configuration according to the documentation.');
    }
}

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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('asf_doc');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
	        ->children()
		        ->arrayNode('supports')
		        	->addDefaultsIfNotSet()
		        	->children()
		        		->booleanNode('asf_ui')->defaultTrue()->end()
		        		->booleanNode('page')->defaultTrue()->end()
		        		->booleanNode('post')->defaultTrue()->end()
		        		->booleanNode('comment')->defaultTrue()->end()
		        		->booleanNode('account')->defaultTrue()->end()
		        		->booleanNode('asf_user')->defaultTrue()->end()
		        		->booleanNode('genemu_form')->defaultTrue()->end()
		        		->booleanNode('tinymce')->defaultTrue()->end()
		        	->end()
	        	->end()
        	->end();
        
        return $treeBuilder;
    }
}

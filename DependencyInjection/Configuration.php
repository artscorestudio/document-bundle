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
        $rootNode = $treeBuilder->root('asf_document');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
	            ->scalarNode('form_theme')
	            	->defaultValue('ASFDocumentBundle:Form:fields.html.twig')
	            ->end()
	            
            	->append($this->addPageParameterNode())
            	->append($this->addPostParameterNode())
            ->end();
        
        return $treeBuilder;
    }
    
    /**
     * Add Page Entity Configuration
     */
    protected function addPageParameterNode()
    {
    	$builder = new TreeBuilder();
    	$node = $builder->root('page');
    
    	$node
	    	->treatTrueLike(array(
	    		'versionable' => false,
	    		'signable' => false,
	    		'form' => array(
	    			'type' => "ASF\DocumentBundle\Form\Type\PageType",
	    			'name' => 'page_type'
	    		)
	    	))
	    	->treatFalseLike(array(
    			'versionable' => false,
    			'signable' => false,
	    		'form' => array(
	    			'type' => "ASF\DocumentBundle\Form\Type\PageType",
	    			'name' => 'page_type'
	    		)
	    	))
	    	->addDefaultsIfNotSet()
	    	->children()
	    		->booleanNode('versionable')
	    			->defaultFalse()
	    		->end()
	    		->booleanNode('signable')
	    			->defaultFalse()
	    		->end()
		    	->arrayNode('form')
			    	->addDefaultsIfNotSet()
			    	->children()
				    	->scalarNode('type')
				    		->defaultValue('ASF\DocumentBundle\Form\Type\PageType')
				    	->end()
				    	->scalarNode('name')
				    		->defaultValue('page_type')
				    	->end()
				    	->arrayNode('validation_groups')
				    		->prototype('scalar')->end()
				    		->defaultValue(array("Default"))
				    	->end()
			    	->end()
		    	->end()
	    	->end()
    	;
    
    	return $node;
    }
    
    /**
     * Add Post Entity Configuration
     */
    protected function addPostParameterNode()
    {
    	$builder = new TreeBuilder();
    	$node = $builder->root('post');
    
    	$node
	    	->treatTrueLike(array(
    			'versionable' => false,
    			'signable' => false,
	    		'form' => array(
	    			'type' => "ASF\DocumentBundle\Form\Type\PostType",
	    			'name' => 'post_type'
	    		)
	    	))
	    	->treatFalseLike(array(
    			'versionable' => false,
    			'signable' => false,
	    		'form' => array(
	    			'type' => "ASF\DocumentBundle\Form\Type\PostType",
	    			'name' => 'post_type'
	    		)
	    	))
	    	->addDefaultsIfNotSet()
	    	->children()
		    	->booleanNode('versionable')
		    		->defaultFalse()
		    	->end()
		    	->booleanNode('signable')
		    		->defaultFalse()
		    	->end()
		    	->arrayNode('form')
			    	->addDefaultsIfNotSet()
			    	->children()
				    	->scalarNode('type')
				    		->defaultValue('ASF\DocumentBundle\Form\Type\PostType')
				    	->end()
				    	->scalarNode('name')
				    		->defaultValue('post_type')
				    	->end()
				    	->arrayNode('validation_groups')
				    		->prototype('scalar')->end()
				    		->defaultValue(array("Default"))
				    	->end()
			    	->end()
		    	->end()
	    	->end()
    	;
    
    	return $node;
    }
}

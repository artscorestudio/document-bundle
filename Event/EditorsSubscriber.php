<?php
/**
 * This file is part of Artscore Studio Framework package
 *
 * (c) 2012-2015 Artscore Studio <info@artscore-studio.fr>
 *
 * This source file is subject to the MIT Licence that is bundled
 * with this source code in the file LICENSE.
 */
namespace ASF\DocumentBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Security\Core\SecurityContextInterface;

use ASF\WebsiteBundle\Event\WebsiteEvents;
use ASF\WebsiteBundle\Event\MenuNodeTypeEvent;

/**
 * Document Editors Event Subscriber
 * 
 * @author Nicolas Claverie <nicolas.claverie@cg31.fr>
 *
 */
class EditorsSubscriber implements EventSubscriberInterface
{
	/**
	 * @var Translator
	 */
	protected $translator;
	
	/**
	 * @var SecurityContextInterface
	 */
	protected $securityContext;
	
	/**
	 * @param Translator $translator
	 */
	public function __construct(Translator $translator, SecurityContextInterface $security_context)
	{
		$this->translator = $translator;
		$this->securityContext = $security_context;
	}
	
	/**
	 * 
	 * @return multitype:multitype:string number
	 */
	public static function getSubscribedEvents()
	{
		return array(
			DocumentEvents::POST_EDITOR_INIT => array('onPostEditorInit', 0),
			WebsiteEvents::MENU_NODE_TYPE => array('onWebsiteMenuNodeType', 0)
		);
	}
	
	/**
	 * Add items to the post editor view
	 * 
	 * @param PostEditorEvent $event
	 */
	public function onPostEditorInit(PostEditorEvent $event)
	{
		$widgets = $event->getWidgets();
		$factory = $event->getWidgetFactory();
	}

	/**
	 * Add links for insert in Menu node types list when creating a website navigation
	 *  
	 * @param MenuNodeTypeEvent $event
	 */
	public function onWebsiteMenuNodeType(MenuNodeTypeEvent $event)
	{
		$node_types = $event->getNodeTypes();
		$node_types['document'] = array(
			'route' => 'asf_doc_page_menu_node_list',
			'name' => $this->translator->trans('Document', array(), 'asf_website'),
			'icon_class' => 'glyphicon glyphicon-file'
		);
		
		$node_types['post'] = array(
			'route' => 'asf_doc_page_menu_node_list',
			'name' => $this->translator->trans('Article', array(), 'asf_website'),
			'icon_class' => 'glyphicon glyphicon-education'
		);
	}
}
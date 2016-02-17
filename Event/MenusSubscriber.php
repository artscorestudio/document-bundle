<?php
/**
 * This file is part of CG31 TransFAST Framework package
 *
 * (c) 2014-2015 Conseil Général de Haute-Garonne
 *
 * This source file is subject to the MIT Licence that is bundled
 * with this source code in the file LICENSE.
 */
namespace ASF\DocumentBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Security\Core\SecurityContextInterface;

use ASF\LayoutBundle\Event\LayoutEvents;
use ASF\LayoutBundle\Event\MainMenuEvent;

/**
 * Document Menus Event Subscriber
 * 
 * @author Nicolas Claverie <nicolas.claverie@cg31.fr>
 *
 */
class MenusSubscriber implements EventSubscriberInterface
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
			LayoutEvents::MAIN_MENU_INIT => array('onMainMenuInit', 0)
		);
	}
	
	/**
	 * Add items to the main menu
	 * 
	 * @param MainMenuEvent $event
	 */
	public function onMainMenuInit(MainMenuEvent $event)
	{
		$root_menu = $event->getMenu();
		$factory = $event->getFactory();
		
		// Crerate menu for Documents
		$menu = $factory->createItem($this->translator->trans('Documents', array(), 'asf_doc'));
		$menu->setExtra('orderNumber', 10);
		$menu->setUri('#');
		$menu->setAttribute('class', 'menu-section');

		// Create menu for website pages
		$page_list = $factory->createItem($this->translator->trans('Pages list', array(), 'asf_doc_page'), array('route' => 'asf_doc_page_list'));
		$menu->addChild($page_list);
		
		// Create menu for blog posts
		$post_list = $factory->createItem($this->translator->trans('Posts list', array(), 'asf_doc_post'), array('route' => 'asf_doc_post_list'));
		$menu->addChild($post_list);
		
		$root_menu->addChild($menu);
	}
}
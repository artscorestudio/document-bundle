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

use Symfony\Component\EventDispatcher\Event;
use Doctrine\Common\Collections\ArrayCollection;

use ASF\DocumentBundle\Document\Widget\WidgetFactory;
use ASF\DocumentBundle\Entity\Post;

/**
 * Artscore Studio Application Sidebar Left Event
 * 
 * @author Nicolas Claverie <nicolas.claverie@cg31.fr>
 *
 */
class PostEditorEvent extends Event
{
	/**
	 * @var ArrayCollection
	 */
	protected $widgets;
	
	/**
	 * @var WidgetFactory
	 */
	protected $widgetFactory;
	
	/**
	 * @var Post
	 */
	protected $post;
	
	/**
	 * @param ArrayCollection $dashboard
	 */
	public function __construct(ArrayCollection $widgets, WidgetFactory $widget_factory, $post)
	{
		$this->widgets = $widgets;
		$this->widgetFactory = $widget_factory;
		$this->post = $post;
	}
	
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getWidgets()
	{
		return $this->widgets;
	}
	
	/**
	 * @return \Asf\Bundle\ApplicationBundle\Application\Widget\WidgetFactory
	 */
	public function getWidgetFactory()
	{
		return $this->widgetFactory;
	}

	/**
	 * @return \Asf\Bundle\DocumentBundle\Entity\Post
	 */
	public function getPost()
	{
		return $this->post;
	}
}
<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use ASF\DocumentBundle\Model\Post\PostInterface;

/**
 * Post Event
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PostEvent extends Event
{
	/**
	 * @var PostInterfac
	 */
	protected $post;
	
	/**
	 * @param PostInterface $post
	 */
	public function __construct(PostInterface $post)
	{
		$this->post = $post;
	}
	
	/**
	 * @return PostInterface
	 */
	public function getPost()
	{
		return $this->post;
	}
}
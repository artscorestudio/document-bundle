<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Model\Document;

/**
 * Document Model
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
abstract class DocumentModel implements DocumentInterface
{
	const STATE_DRAFT = 'draf';
	const STATE_WAITING = 'waiting';
	const STATE_PUBLISHED = 'published';
	
	/**
	 * @var integer
	 */
	protected $id;
	
	/**
	 * @var string
	 */
	protected $title;
	
	/**
	 * @var string
	 */
	protected $content;
	
	/**
	 * @var string
	 */
	protected $slug;
	
	/**
	 * @var string
	 */
	protected $state;
	
	/**
	 * @var \DateTime
	 */
	protected $createdAt;
	
	/**
	 * @var \DateTime
	 */
	protected $updatedAt;
	
	/**
	 * @var string
	 */
	protected $discr;
	
	public function __construct()
	{
		$this->createdAt = new \DateTime();
		$this->updatedAt = new \DateTime();
		$this->state = self::STATE_DRAFT;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::getId()
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::getTitle()
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::setTitle()
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::getContent()
	 */
	public function getContent()
	{
		return $this->content;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::setContent()
	 */
	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::getSlug()
	 */
	public function getSlug()
	{
		return $this->slug;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::setSlug()
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::getState()
	 */
	public function getState()
	{
		return $this->state;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::setState()
	 */
	public function setState($state)
	{
		$this->state = $state;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::getCreatedAt()
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::setCreatedAt()
	 */
	public function setCreatedAt($created_at)
	{
		$this->createdAt = $created_at;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::getUpdatedAt()
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ASF\DocumentBundle\Model\Document\DocumentInterface::setUpdatedAt()
	 */
	public function setUpdatedAt($updated_at)
	{
		$this->updatedAt = $updated_at;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getDiscr()
	{
		return $this->discr;
	}
	
	/**
	 * @param string $discr
	 * @return ASF\DocumentBundle\Model\Document\DocumentModel
	 */
	public function setDiscr($discr)
	{
		$this->discr = $discr;
		return $this;
	}
	
	/**
	 * Return allowed states for entity validation
	 * 
	 * @return array
	 */
	public static function getStates()
	{
		return array(
			self::STATE_DRAFT,
			self::STATE_WAITING,
			self::STATE_PUBLISHED
		);
	}
	
	/**
	 * Do stuff on prePersist
	 */
	public function onPrePersist()
	{
		$this->createdAt = new \DateTime();
	}
}
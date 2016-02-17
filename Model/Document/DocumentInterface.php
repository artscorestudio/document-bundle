<?php
/**
 * This file is part of Artscore Studio Framework package
 *
 * (c) 2012-2015 Artscore Studio <info@artscore-studio.fr>
 *
 * This source file is subject to the MIT Licence that is bundled
 * with this source code in the file LICENSE.
 */
namespace ASF\DocumentBundle\Model\Document;

/**
 * Document Interface
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
interface DocumentInterface
{
	/**
	 * @return integer
	 */
	public function getId();
	
	/**
	 * @return string
	 */
	public function getTitle();
	
	/**
	 * @param string $title
	 * @return ASF\DocumentBundle\Model\Document\DocumentInterface
	 */
	public function setTitle($title);
	
	/**
	 * @return string
	 */
	public function getContent();
	
	/**
	 * @param string $content
	 * @return ASF\DocumentBundle\Model\Document\DocumentInterface
	 */
	public function setContent($content);
	
	/**
	 * @return string
	 */
	public function getSlug();
	
	/**
	 * @param string $slug
	 * @return ASF\DocumentBundle\Model\Document\DocumentInterface
	 */
	public function setSlug($slug);
	
	/**
	 * @return string
	 */
	public function getState();
	
	/**
	 * @param string $state
	 * @return ASF\DocumentBundle\Model\Document\DocumentInterface
	 */
	public function setState($state);
	
	/**
	 * @return \DateTime
	 */
	public function getCreatedAt();
	
	/**
	 * @param \DateTime $date
	 * @return ASF\DocumentBundle\Model\Document\DocumentInterface
	 */
	public function setCreatedAt($date);
	
	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt();
	
	/**
	 * @param \DateTime $date
	 * @return ASF\DocumentBundle\Model\Document\DocumentInterface
	 */
	public function setUpdatedAt($date);
}
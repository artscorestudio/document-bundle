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
 * Document Interface
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
interface DocumentAuthorInterface
{
	/**
	 * Get the ID of the document's author
	 *
	 * @return integer
	 */
	public function getId();
	
	/**
	 * Get the name of the author
	 * 
	 * @return string
	 */
	public function getName();
	
	/**
	 * Set the name of the author
	 * 
	 * @param \ASF\DocumentBundle\Model\Document\DocumentAuthorInterface $author
	 * @return \ASF\DocumentBundle\Model\Document\SignableInterface
	 */
	public function setName($name);
}
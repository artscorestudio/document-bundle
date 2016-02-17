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
 * Interface for Document entities with versionning system
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
interface VersionableInterface
{
	/**
	 * @return ASF\DocumentBundle\Model\Document\DocumentInterface
	 */
	public function getOriginal();
	
	/**
	 * @param ASF\DocumentBundle\Model\Document\DocumentInterface $original
	 * @return ASF\DocumentBundle\Model\Document\DocumentInterface
	 */
	public function setOriginal($original);
	
	/**
	 * Clear the Id for create new entity version
	 */
	public function clearId();
	
	/**
	 * Reset the ID of the cloned object (id = null)
	 * @return void
	 */
	public function __clone();
}
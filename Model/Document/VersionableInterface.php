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
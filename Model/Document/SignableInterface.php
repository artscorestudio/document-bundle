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
 * Signable Interface
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
interface SignableInterface
{
	/**
	 * Return the author of the document
	 * 
	 * @return \ASF\DocumentBundle\Model\Document\DocumentAuthorInterface
	 */
	public function getAuthor();
	
	/**
	 * Set the author of the document
	 * 
	 * @param \ASF\DocumentBundle\Model\Document\DocumentAuthorInterface $author
	 * @return \ASF\DocumentBundle\Model\Document\SignableInterface
	 */
	public function setAuthor(DocumentAuthorInterface $author);
}
<?php
/**
 * This file is part of Artscore Studio Framework package
 *
 * (c) 2012-2015 Artscore Studio <info@artscore-studio.fr>
 *
 * This source file is subject to the MIT Licence that is bundled
 * with this source code in the file LICENSE.
 */
namespace ASF\DocumentBundle\Model\Page;

/**
 * Interface for the relation between Page entity and entity used for author attribute
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
interface PageInterface
{
	/**
	 * @return ASF\DocumentBundle\Model\Page\PageAuthorInterface
	 */
	public function getAuthor();
	
	/**
	 * @param ASF\DocumentBundle\Model\Page\PageAuthorInterface $author
	 * @return ASF\DocumentBundle\Model\Page\PageInterface
	 */
	public function setAuthor($author);
}
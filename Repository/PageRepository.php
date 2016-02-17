<?php
/**
 * This file is part of Artscore Studio Framework package
 *
 * (c) 2012-2015 Artscore Studio <info@artscore-studio.fr>
 *
 * This source file is subject to the MIT Licence that is bundled
 * with this source code in the file LICENSE.
 */
namespace ASF\DocumentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * Page Entity Repository
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PageRepository extends EntityRepository
{
	/**
	 * Get last version for a page
	 * 
	 * @param integer $id AsfDocumentBundle:Page  ID
	 */
	public function getLastVersion($page_id)
	{
		$qb = $this->createQueryBuilder('p');
		
		$qb->where('ASFDocumentBundle:Page', 'o', 'p.original=:page_id')
			->orderBy('p.createdAt', 'DESC')
			->setParameter(':page_id', $page_id);
		
		$result = $qb->getQuery()->setMaxResults(1)->getResult(Query::HYDRATE_OBJECT);
		
		if ( is_null($result) ) {
			$qb2 = $this->createQueryBuilder('p');
			$qb2->where('p.id=:page_id')->setParameter(':page_id', $page_id);
			
			$result = $qb->getQuery()->getResult(Query::HYDRATE_OBJECT);
		}
		
		return $result;
	}
}
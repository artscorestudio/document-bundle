<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use ASF\DocumentBundle\Model\Document\DocumentModel;

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
	 * @param integer $id ASFDocumentBundle:Page  ID
	 */
	public function getLastVersion($page_id)
	{
		$qb = $this->createQueryBuilder('p');
		
		$qb->where('p.original=:page_id')
			->orderBy('p.createdAt', 'DESC')
			->setParameter(':page_id', $page_id);
		
		$result = $qb->getQuery()->setMaxResults(1)->getResult();

		if ( is_null($result) ) {
			$qb2 = $this->createQueryBuilder('p');
			$qb2->where('p.id=:page_id')->setParameter(':page_id', $page_id);
			
			$result = $qb->getQuery()->getResult();
		}
		
		return $result[0];
	}
	
	/**
	 * Get all pages in their last version
	 */
	public function getAllLastVersion()
	{
		$qb = $this->createQueryBuilder('p');
		$qb->where('p.original IS NULL AND p.state=:state')
			->setParameter(':state', DocumentModel::STATE_PUBLISHED);
		
		$result = $qb->getQuery()->getResult();
		$return = array();
		foreach($result as $original) {
			$return[] = $this->getLastVersion($original->getId());
		}
		return $return;
	}
	
	/**
	 * Find page by slug
	 *
	 * @param string $path
	 */
	public function findBySlug($path)
	{
		$qb = $this->createQueryBuilder('p');
		$qb instanceof QueryBuilder;
	
		$qb->add('where', $qb->expr()->like('p.slug', $qb->expr()->lower(':searched_term')))
			->setParameter('searched_term', $path);
	
		return $qb->getQuery()->getResult();
	}
}
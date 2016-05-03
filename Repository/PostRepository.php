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
use ASF\DocumentBundle\Model\Document\DocumentModel;

/**
 * Post Entity Repository
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PostRepository extends EntityRepository
{
	/**
	 * Get last version for a post
	 * 
	 * @param integer $id AsfDocumentBundle:Page  ID
	 */
	public function getLastVersion($post_id)
	{
		$qb = $this->createQueryBuilder('p');
		
		$qb->where('p.original=:post_id')
			->orderBy('p.createdAt', 'DESC')
			->setParameter(':post_id', $post_id);
		
		$result = $qb->getQuery()->setMaxResults(1)->getResult(Query::HYDRATE_OBJECT);
		
		if ( is_null($result) ) {
			$qb2 = $this->createQueryBuilder('p');
			$qb2->where('p.id=:post_id')->setParameter(':post_id', $post_id);
			
			$result = $qb->getQuery()->getResult(Query::HYDRATE_OBJECT);
		}
		
		return $result;
	}
	
	/**
	 * Get all posts in their last version
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
}
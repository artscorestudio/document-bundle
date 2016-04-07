<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Entity\Manager;

use ASF\CoreBundle\Entity\Manager\ASFEntityManagerInterface;

/**
 * Document Manager Interface
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
interface ASFDocumentEntityManagerInterface extends ASFEntityManagerInterface
{
    /**
     * Return the entity class name
     *
     * @return string
     */
    public function getClassName();
    
    /**
     * Return the repository for the entity managed by the Entity Manager
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository();
}
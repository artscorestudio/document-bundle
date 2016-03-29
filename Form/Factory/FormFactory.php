<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface;

/**
 * Scheduler Bundle form factory
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class FormFactory implements FactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $type;
    
    /**
     * @var array
     */
    private $validationGroups;
    
    /**
     * @param FormFactoryInterface $formFactory
     * @param string               $name
     * @param string               $type
     * @param array                $validationGroups
     */
    public function __construct(FormFactoryInterface $formFactory, $name, $type, array $validationGroups = null)
    {
        $this->formFactory = $formFactory;
        $this->name = $name;
        $this->type = $type;
        $this->validationGroups = $validationGroups;
    }
    
    /**
     * {@inheritDoc}
     * @see \ASF\DocumentBundle\Form\Factory\FormFactoryInterface::createForm()
     */
    public function createForm(array $options = array())
    {
        $options = array_merge(array('validation_groups' => $this->validationGroups), $options);
        
        return $this->formFactory->createNamed($this->name, $this->type, null, $options);
    }
}
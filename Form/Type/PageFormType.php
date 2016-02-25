<?php
/**
 * This file is part of Artscore Studio Framework package
 *
 * (c) 2012-2015 Artscore Studio <info@artscore-studio.fr>
 *
 * This source file is subject to the MIT Licence that is bundled
 * with this source code in the file LICENSE.
 */
namespace ASF\DocumentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use ASF\DocumentBundle\Model\Document\DocumentModel;

/**
 * Page Form Type
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PageFormType extends AbstractType
{
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', TextType::class, array(
			'label' => 'Title',
			'required' => true,
			'attr' => array('class' => 'doc-title')
		))
		->add('slug', TextType::class, array(
			'label' => 'Slug',
			'required' => true,
			'attr' => array('class' => 'doc-slug')
		))
		->add('state', ChoiceType::class, array(
			'label' => 'State',
			'required' => true,
			'choices' => array(
				DocumentModel::STATE_DRAFT => 'Draft', 
				DocumentModel::STATE_WAITING => 'Waiting', 
				DocumentModel::STATE_PUBLISHED => 'Published'
			),
			'preferred_choices' => array(DocumentModel::STATE_DRAFT)
		));
		
		$builder->add('content', TextareaType::class, array(
			'label' => 'Content',
			'required' => false,
			'attr' => array('class' => 'tinymce-content doc-content')
		));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'translation_domain' => 'asf_doc_page'
		));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName()
	{
		return 'asf_doc_page_form';
	}
}
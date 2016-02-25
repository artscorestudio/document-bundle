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
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use ASF\DocumentBundle\Model\Document\DocumentModel;
use ASF\CoreBundle\Model\Manager\ASFEntityManagerInterface;

/**
 * Post Form Type
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PostFormType extends AbstractType
{
	/**
	 * @var ASFEntityManagerInterface
	 */
	protected $postMaganer;
	
	/**
	 * @param ASFEntityManagerInterface $post_manager
	 */
	public function __construct($post_manager)
	{
		$this->postManager = $post_manager;
	}
	
	/*
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::buildView()
	 */
	public function buildView(FormView $view, FormInterface $form, array $options) {
		//$view->vars['isAccountActivated'] = $this->isAccountActivated;
		//$view->vars['isAsfUserSupport'] = $this->isAsfUserSupport;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text', array(
			'label' => 'Title',
			'required' => true,
			'max_length' => 255,
			'attr' => array('class' => 'doc-title')
		))
		->add('slug', 'text', array(
			'label' => 'Slug',
			'required' => true,
			'attr' => array('class' => 'doc-slug')
		))
		->add('state', 'choice', array(
			'label' => 'State',
			'required' => true,
			'choices' => array(
				DocumentModel::STATE_DRAFT => 'Draft', 
				DocumentModel::STATE_WAITING => 'Waiting', 
				DocumentModel::STATE_PUBLISHED => 'Published'
			),
			'preferred_choices' => array(DocumentModel::STATE_DRAFT)
		))
		->add('content', 'textarea', array(
			'label' => 'Content',
			'required' => false,
			'attr' => array('class' => 'tinymce-content doc-content')
		));
		
		//if ( true === $this->isAccountActivated && true == $this->isAsfUserSupport ) {
			//$builder->add('author', 'asf_user_search_user');
		//}
		
		$builder->add('save', 'submit', array(
			'label' => 'Save',
			'translation_domain' => 'asf_doc'
		));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => $this->postManager->getClassName(),
			'submit_label' => 'add',
			'translation_domain' => 'asf_doc_post'
		));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName()
	{
		return 'asf_doc_post_form';
	}
}
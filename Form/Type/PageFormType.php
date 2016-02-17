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
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

use ASF\DocumentBundle\Entity\Manager\PageManager;
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
	 * @var PageManager
	 */
	protected $pageManager;
	
	/**
	 * @var SecurityContext
	 */
	protected $securityContext;
	
	/**
	 * @var boolean
	 */
	protected $isAccountActivated;
	
	/**
	 * @var boolean
	 */
	protected $isAsfUserSupport;
	
	/**
	 * @var boolean
	 */
	protected $isGenemuFormSupport;
	
	/**
	 * @param PageManager     $page_manager
	 * @param SecurityContext $security_context
	 * @param boolean         $is_account_activated
	 * @param boolean         $is_asf_user_support
	 * @param boolean         $is_genemu_form_activated
	 */
	public function __construct(PageManager $page_manager, SecurityContext $security_context, $is_account_activated, $is_asf_user_support, $is_genemu_form_activated)
	{
		$this->pageManager = $page_manager;
		$this->securityContext = $security_context;
		$this->isAccountActivated = $is_account_activated;
		$this->isAsfUserSupport = $is_asf_user_support;
		$this->isGenemuFormSupport = $is_genemu_form_activated;
	}
	
	/*
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::buildView()
	 */
	public function buildView(FormView $view, FormInterface $form, array $options) {
		$view->vars['isAccountActivated'] = $this->isAccountActivated;
		$view->vars['isAsfUserSupport'] = $this->isAsfUserSupport;
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
		));
		
		$builder->add('content', 'textarea', array(
			'label' => 'Content',
			'required' => false,
			'attr' => array('class' => 'tinymce doc-content')
		));
			
		if ( true === $this->isAccountActivated && true == $this->isAsfUserSupport ) {
			$builder->add('author', 'asf_user_search_user');
		}
		
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
			'data_class' => $this->pageManager->getClassName(),
			'submit_label' => 'add',
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
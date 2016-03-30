<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use ASF\DocumentBundle\Model\Document\DocumentModel;
use ASF\DocumentBundle\Entity\Manager\ASFDocumentEntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


/**
 * Post Form Type
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PostType extends AbstractType
{
	/**
	 * @var ASFDocumentEntityManagerInterface
	 */
	protected $postMaganer;
	
	/**
	 * @param ASFDocumentEntityManagerInterface $postManager
	 */
	public function __construct($postManager)
	{
		$this->postManager = $postManager;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', TextType::class, array(
			'label' => 'Post title',
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
				'Draft' => DocumentModel::STATE_DRAFT, 
				'Waiting' => DocumentModel::STATE_WAITING, 
				'Published' => DocumentModel::STATE_PUBLISHED
			),
			'preferred_choices' => array(DocumentModel::STATE_DRAFT)
		))
		->add('content', TextareaType::class, array(
			'label' => 'Content',
			'required' => false,
			'attr' => array('class' => 'tinymce-content doc-content')
		));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::setDefaultOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => $this->postManager->getClassName(),
			'translation_domain' => 'asf_document'
		));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName()
	{
		return 'post_type';
	}
}
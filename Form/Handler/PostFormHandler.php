<?php
/**
 * This file is part of Artscore Studio Framework package
 *
 * (c) 2012-2015 Artscore Studio <info@artscore-studio.fr>
 *
 * This source file is subject to the MIT Licence that is bundled
 * with this source code in the file LICENSE.
 */
namespace ASF\DocumentBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;

use ASF\CoreBundle\Application\Form\FormHandlerModel;
use ASF\DocumentBundle\Entity\Manager\PostManager;
use ASF\LayoutBundle\Session\FlashMessage;

/**
 * Post Form Handler
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PostFormHandler extends FormHandlerModel
{
	/**
	 * @var PostManager
	 */
	protected $postManager;
	
	/**
	 * @var MutableAclInterface
	 */
	protected $aclProvider;
	
	/**
	 * @var FlashMessage
	 */
	protected $flashMessage;
	
	/**
	 * @param FormInterface $form
	 * @param Request $request
	 * @param PageManager $page_manager
	 * @param MutableAclProviderInterface $acl_provider
	 */
	public function __construct(FormInterface $form, PostManager $post_manager, MutableAclProviderInterface $acl_provider)
	{
		parent::__construct($form);
		$this->postManager = $post_manager;
		$this->aclProvider = $acl_provider;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Asf\Bundle\ApplicationBundle\Application\Form\FormHandlerModel::processForm()
	 * @throw \Exception
	 */
	public function processForm($model)
	{
		try {
			return true;
			
		} catch (\Exception $e) {
			throw new \Exception(sprintf('An error occured : %s', $e->getMessage()));
		}
		
		return false;
	}
}
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
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;

use ASF\CoreBundle\Application\Form\FormHandlerModel;
use ASF\DocumentBundle\Entity\Manager\PageManager;
use ASF\LayoutBundle\Session\FlashMessage;

/**
 * Page Form Handler
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PageFormHandler extends FormHandlerModel
{
	/**
	 * @var PageManager
	 */
	protected $pageManager;
	
	/**
	 * @var MutableAclInterface
	 */
	protected $aclProvider;
	
	/**
	 * @param FormInterface $form
	 * @param PageManager $page_manager
	 * @param MutableAclProviderInterface $acl_provider
	 */
	public function __construct(FormInterface $form, PageManager $page_manager, MutableAclProviderInterface $acl_provider)
	{
		parent::__construct($form);
		$this->pageManager = $page_manager;
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
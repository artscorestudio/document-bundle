<?php
/**
 * This file is part of Artscore Studio Framework package
 *
 * (c) 2012-2015 Artscore Studio <info@artscore-studio.fr>
 *
 * This source file is subject to the MIT Licence that is bundled
 * with this source code in the file LICENSE.
 */
namespace ASF\DocumentBundle\Controller;

use ASF\CoreBundle\Controller\ASFController;

/**
 * Backend Controller
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class BackendController extends ASFController
{
	/**
	 * Backend homepage for Document Bundle
	 */
	public function indexAction()
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page !');
		
		return $this->render('ASFDocumentBundle:Backend:index.html.twig');
	}
	
}
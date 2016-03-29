<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Default Controller
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class DefaultController extends Controller
{
	/**
	 * Backend homepage for Document Bundle
	 */
	public function indexAction()
	{
		return $this->render('ASFDocumentBundle:Default:index.html.twig');
	}
}
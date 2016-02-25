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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Action\RowAction;

use Doctrine\ORM\QueryBuilder;

use ASF\DocumentBundle\Model\Document\DocumentModel;
use ASF\DocumentBundle\Event\DocumentEvents;
use ASF\DocumentBundle\Form\Type\PageFormType;

/**
 * Artscore Studio Page Controller
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PageController extends Controller
{
	/**
	 * List all website pages
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function listAction()
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page !');
		
		// Define DataGrid Source
		$source = new Entity($this->get('asf_doc.page.manager')->getClassName());

		// Get datagrid
		$grid = $this->get('grid');
		$grid instanceof Grid;
		$grid->setSource($source);
		$tableAlias = $source->getTableAlias(); 
		$pageClassName = $this->get('asf_doc.page.manager')->getShortClassName();
		
		$source->manipulateQuery(function($query) use ($tableAlias, $pageClassName) {
			$query instanceof QueryBuilder;
			
			// Get all original version of each pages
			$query2 = $query->getEntityManager()->createQueryBuilder();
			$query2->select('page.id')
				->from($pageClassName, 'page')
				->where('page.original IS NULL');
			$result = $query2->getQuery()->getResult();
			
			// Search versions for each original pages
			if (count($result)) {
				$ids = array();
				foreach($result as $original) {
					$qb = $query->getEntityManager()->createQueryBuilder();
					$qb->select('p')->from($pageClassName, 'p')
						->where('p.original=:original_id')
						->orderBy('p.createdAt', 'DESC')
						->setParameter('original_id', $original['id']);
					$r = $qb->getQuery()->setMaxResults(1)->getResult();
					
					if ( count($r) ) {
						$ids[] = $r[0]->getId();
					} else {
						$ids[] = $original['id'];
					}
				}
				
				// Create query for datagird
				$query->add('where', $query->expr()->in($tableAlias.'.id', $ids));
			}
			if ( count($query->getDQLPart('orderBy')) == 0) {
				$query->orderBy($tableAlias.'.createdAt', 'DESC');
			}
		});
		
		// Grid Columns configuration
		$grid->getColumn('id')->setVisible(false);
		$grid->getColumn('title')->setTitle($this->get('translator')->trans('Title', array(), 'asf_doc'));
		$grid->getColumn('content')->setVisible(false);
		$grid->getColumn('slug')->setTitle($this->get('translator')->trans('Slug', array(), 'asf_doc_page'));
		$grid->getColumn('state')->setTitle($this->get('translator')->trans('State', array(), 'asf_doc'))
			->setFilterType('select')->setSelectFrom('values')->setOperatorsVisible(false)
			->setDefaultOperator('eq')->setValues(array(
				DocumentModel::STATE_DRAFT => $this->get('translator')->trans('Draft', array(), 'asf_doc'),
				DocumentModel::STATE_WAITING => $this->get('translator')->trans('Waiting', array(), 'asf_doc'),
				DocumentModel::STATE_PUBLISHED => $this->get('translator')->trans('Published', array(), 'asf_doc')
			));
		$grid->getColumn('createdAt')->setTitle($this->get('translator')->trans('Created at', array(), 'asf_doc'));
		$grid->getColumn('updatedAt')->setTitle($this->get('translator')->trans('Updated at', array(), 'asf_doc'));
		
		$edit_action = new RowAction('btn_edit', 'asf_doc_page_edit');
		$edit_action->setRouteParameters(array('id'));
		$grid->addRowAction($edit_action);
		
		$delete_action = new RowAction('btn_delete', 'asf_doc_page_delete', true);
		$delete_action->setRouteParameters(array('id'))
			->setConfirmMessage($this->get('translator')->trans('Do you want to delete this %name% ?', array('%name%' => $this->get('translator')->trans('this page', array(), 'asf_doc_page')), 'asf_doc'));
		$grid->addRowAction($delete_action);
		
		$grid->setNoDataMessage($this->get('translator')->trans('No page was found.', array(), 'asf_doc_page'));
		
		return $grid->getGridResponse('ASFDocumentBundle:Page:list.html.twig', array('grid' => $grid));
	}
	
	/**
	 * Add or edit a page
	 * 
	 * @param  integer $id           ASFDocumentBundle:Page Entity ID
	 * @throws AccessDeniedException If user does not have ACL's rights for edit the page
	 * @throws \Exception            Error on page's author not found or page not found  
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction(Request $request, $id = null)
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page !');
		
		if ( !is_null($id) ) {
			$original = $this->get('asf_doc.page.manager')->getRepository()->findOneBy(array('id' => $id));
			
			$page = clone $original;
			$this->get('asf_doc.page.manager')->getEntityManager()->detach($page);
			if ( is_null($original->getOriginal()) )
				$page->setOriginal($original);
			else
				$page->setOriginal($original->getOriginal());
			
			if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
				throw new AccessDeniedException();
			
			$success_message = $this->get('translator')->trans('Updated successfully', array(), 'asf_doc_page');
		} else {
			$page = $this->get('asf_doc.page.manager')->createInstance();
			$page->setTitle($this->get('translator')->trans('New page', array(), 'asf_doc_page'))->setSlug($this->get('translator')->trans('new-page', array(), 'asf_doc_page'));
			$success_message = $this->get('translator')->trans('Created successfully', array(), 'asf_doc_page');
		}
		
		if ( is_null($page) )
			throw new \Exception($this->get('translator')->trans('An error occurs when generating or getting the page', array(), 'asf_doc_page'));

		$form = $this->createForm(PageFormType::class, $page, array('data_class' => $this->get('asf_doc.page.manager')->getClassName()));
		$form->handleRequest($request);
		
		if ( $form->isSubmitted() && $form->isValid() ) {
			try {
				print_r( $page );
				throw new \Exception('NICOLAS');
				$this->get('asf_doc.page.manager')->getEntityManager()->persist($page);
				$this->get('asf_doc.page.manager')->getEntityManager()->flush();
				$this->get('asf_layout.flash_message')->success($success_message);
				return $this->redirect($this->get('router')->generate('asf_doc_page_edit', array('id' => $page->getId())));
				
			} catch(\Exception $e) {
				$this->get('asf_layout.flash_message')->danger($e->getMessage());
			}
		}
		
		return $this->render('ASFDocumentBundle:Page:edit.html.twig', array(
			'page' => $page, 
			'form' => $form->createView()
		));
	}
	
	/**
	 * Delete a page
	 * 
	 * @param  integer $id           ASFDocumentBundle:Page Entity ID
	 * @throws AccessDeniedException If user does not have ACL's rights for delete the page
	 * @throws \Exception            Error on page not found or on removing element from DB
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id)
	{
		$securityContext = $this->get('security.context');
		$page = $this->get('asf_doc.page.manager')->getRepository()->findOneBy(array('id' => $id));
		if (false === $securityContext->isGranted('DELETE', $page))
			throw new AccessDeniedException();
		
		try {
			$this->get('asf_doc.page.manager')->getEntityManager()->remove($page);
			$this->get('asf_doc.page.manager')->getEntityManager()->flush();
			
			$this->get('asf_layout.flash_message')->success($this->getTranslator()->trans('The page "%name%" successfully deleted', array('%name%' => $page->getTitle()), 'asf_doc_page'));
			
		} catch (\Exception $e) {
			$this->get('asf_layout.flash_message')->danger($e->getMessage());
		}
		
		return $this->redirect($this->get('router')->generate('asf_doc_page_list'));
	}
}
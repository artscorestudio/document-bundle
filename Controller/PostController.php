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

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Action\RowAction;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;

use ASF\DocumentBundle\Entity\Post;
use ASF\DocumentBundle\Event\DocumentEvents;
use ASF\DocumentBundle\Event\PostEditorEvent;
use ASF\DocumentBundle\Model\Document\DocumentModel;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use ASF\DocumentBundle\Model\Document\VersionableInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query\Expr;
use APY\DataGridBundle\Grid\Column\Column;

/**
 * Artscore Studio Post Controller
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PostController extends Controller
{
	/**
	 * List all blog posts
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function listAction()
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page !');
		
		$postManager = $this->get('asf_document.post.manager');
		
		// Define DataGrid Source
		$source = new Entity($postManager->getClassName());
		
		// Get datagrid
		$grid = $this->get('grid');
		$grid instanceof Grid;
		$grid->setSource($source);
		$tableAlias = $source->getTableAlias();
		$postClassName = $postManager->getShortClassName();
		
		$source->manipulateQuery(function($query) use ($tableAlias, $postClassName) {
			$query instanceof QueryBuilder;
			
			// Get all original version of each posts
			$query2 = $query->getEntityManager()->createQueryBuilder();
			$query2->select('post.id')
				->from($postClassName, 'post')
				->where('post.original IS NULL');
			$result = $query2->getQuery()->getResult();
			
			// Search versions for each original posts
			if (count($result)) {
				$ids = array();
				foreach($result as $original) {
					$qb = $query->getEntityManager()->createQueryBuilder();
					$qb->select('p')->from($postClassName, 'p')
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
		$grid->getColumn('title')->setTitle($this->get('translator')->trans('Post title', array(), 'asf_document'));
		$grid->getColumn('content')->setVisible(false);
		$grid->getColumn('slug')->setTitle($this->get('translator')->trans('Slug', array(), 'asf_document'));
		$grid->getColumn('state')->setTitle($this->get('translator')->trans('State', array(), 'asf_document'))
			->setFilterType('select')->setSelectFrom('values')->setOperatorsVisible(false)
			->setDefaultOperator('eq')->setValues(array(
				DocumentModel::STATE_DRAFT => $this->get('translator')->trans('Draft', array(), 'asf_document'),
				DocumentModel::STATE_WAITING => $this->get('translator')->trans('Waiting', array(), 'asf_document'),
				DocumentModel::STATE_PUBLISHED => $this->get('translator')->trans('Published', array(), 'asf_document')
			));
		$grid->getColumn('createdAt')->setTitle($this->get('translator')->trans('Created at', array(), 'asf_document'));
		$grid->getColumn('updatedAt')->setTitle($this->get('translator')->trans('Updated at', array(), 'asf_document'));
		
		$edit_action = new RowAction('btn_edit', 'asf_document_post_edit');
		$edit_action->setRouteParameters(array('id'));
		$grid->addRowAction($edit_action);
		
		$delete_action = new RowAction('btn_delete', 'asf_document_post_delete', true);
		$delete_action->setRouteParameters(array('id'))
			->setConfirmMessage($this->get('translator')->trans('Do you want to delete this post?', array(), 'asf_document'));
		$grid->addRowAction($delete_action);
		
		$grid->setNoDataMessage($this->get('translator')->trans('No post was found.', array(), 'asf_document'));
		
		return $grid->getGridResponse('ASFDocumentBundle:Post:list.html.twig', array('grid' => $grid));
	}
	
	/**
	 * Add or edit a post
	 * 
	 * @param  integer $id           ASFDocumentBundle:Post Entity ID
	 * @throws AccessDeniedException If user does not have ACL's rights for edit the post
	 * @throws \Exception            Error on post's author not found or post not found  
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editAction(Request $request, $id = null)
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page !');
		$postManager = $this->get('asf_document.post.manager');
		
		if ( !is_null($id) ) {
			$original = $postManager->getRepository()->findOneBy(array('id' => $id));
			
			if ( $this->getParameter('asf_document.post.versionable') === true ) {
				$post = clone $original;
				$postManager->getEntityManager()->detach($post);
				if ( is_null($original->getOriginal()) )
					$post->setOriginal($original);
				else
					$post->setOriginal($original->getOriginal());
			} else {
				$post = $original;
			}
			
			$success_message = $this->get('translator')->trans('Updated successfully', array(), 'asf_document');
			
		} else {
			$post = $postManager->createInstance();
			$post->setTitle($this->get('translator')->trans('New post', array(), 'asf_document'))->setSlug($this->get('translator')->trans('new-post', array(), 'asf_document'));
			$success_message = $this->get('translator')->trans('Created successfully', array(), 'asf_document');
		}
		
		if ( is_null($post) )
			throw new \Exception($this->get('translator')->trans('An error occurs when generating or getting the post', array(), 'asf_document'));

		$formFactory = $this->get('asf_document.form.factory.post');
		$form = $formFactory->createForm();
		$form->setData($post);
		
		$form->handleRequest($request);
		
		if ( $form->isSubmitted() && $form->isValid() ) {
			try {
				if ( is_null($post->getId()) ) {
					$postManager->getEntityManager()->persist($post);
				}
				$postManager->getEntityManager()->flush();
				
				if ( $this->has('asf_layout.flash_message') ) {
					$this->get('asf_layout.flash_message')->success($success_message);
				}
				
				return $this->redirect($this->get('router')->generate('asf_document_post_edit', array('id' => $post->getId())));
				
			} catch (\Exception $e) {
				if ( $this->has('asf_layout.flash_message') ) {
					$this->get('asf_layout.flash_message')->danger($e->getMessage());
				}
			}
		}
		
		return $this->render('ASFDocumentBundle:Post:edit.html.twig', array(
			'post' => $post, 
			'form' => $form->createView()
		));
	}
	
	/**
	 * Delete a post
	 * 
	 * @param  integer $id           ASFDocumentBundle:Post Entity ID
	 * @throws AccessDeniedException If user does not have ACL's rights for delete the post
	 * @throws \Exception            Error on post not found or on removing element from DB
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id)
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page !');
		$postManager = $this->get('asf_document.post.manager');
		
		$post = $postManager->getRepository()->findOneBy(array('id' => $id));

		try {
			if ( is_null($post) )
				throw new \Exception($this->get('translator')->trans('An error occurs when deleting the post', array(), 'asf_document'));
			
			$postManager->getEntityManager()->remove($post);
			$postManager->getEntityManager()->flush();
			
			if ( $this->has('asf_layout.flash_message') ) {
				$this->get('asf_layout.flash_message')->success($this->get('translator')->trans('The post "%name%" successfully deleted', array('%name%' => $post->getTitle()), 'asf_document'));
			}
			
		} catch (\Exception $e) {
			if ( $this->has('asf_layout.flash_message') ) {
				$this->get('asf_layout.flash_message')->danger($e->getMessage());
			}
		}
		
		return $this->redirect($this->get('router')->generate('asf_document_post_list'));
	}
}
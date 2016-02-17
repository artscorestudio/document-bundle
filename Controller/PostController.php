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

/**
 * Artscore Studio Post Controller
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PostController extends ASFController
{
	/**
	 * List all blog posts
	 * 
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function listAction()
	{
		// Define DataGrid Source
		$source = new Entity($this->get('asf_doc.post.manager')->getClassName());
			 
		// Get datagrid
		$grid = $this->get('grid');
		$grid instanceof Grid;
		$grid->setSource($source);
		$tableAlias = $source->getTableAlias();
		$postClassName = $this->get('asf_doc.post.manager')->getShortClassName();
		
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
		$grid->getColumn('title')->setTitle($this->getTranslator()->trans('Title', array(), 'asf_doc'));
		$grid->getColumn('content')->setVisible(false);
		$grid->getColumn('slug')->setTitle($this->getTranslator()->trans('Slug', array(), 'asf_doc_post'));
		$grid->getColumn('state')->setTitle($this->getTranslator()->trans('State', array(), 'asf_doc'))
			->setFilterType('select')->setSelectFrom('values')->setOperatorsVisible(false)
			->setDefaultOperator('eq')->setValues(array(
				DocumentModel::STATE_DRAFT => $this->getTranslator()->trans('Draft', array(), 'asf_doc'),
				DocumentModel::STATE_WAITING => $this->getTranslator()->trans('Waiting', array(), 'asf_doc'),
				DocumentModel::STATE_PUBLISHED => $this->getTranslator()->trans('Published', array(), 'asf_doc')
			));
		$grid->getColumn('createdAt')->setTitle($this->getTranslator()->trans('Created at', array(), 'asf_doc'));
		$grid->getColumn('updatedAt')->setTitle($this->getTranslator()->trans('Updated at', array(), 'asf_doc'));
		
		$edit_action = new RowAction('btn_edit', 'asf_doc_post_edit');
		$edit_action->setRouteParameters(array('id'));
		$grid->addRowAction($edit_action);
		
		$delete_action = new RowAction('btn_delete', 'asf_doc_post_delete', true);
		$delete_action->setRouteParameters(array('id'))
			->setConfirmMessage($this->getTranslator()->trans('Do you want to delete this %name% ?', array('%name%' => $this->getTranslator()->trans('this post', array(), 'asf_doc_post')), 'asf_doc'));
		$grid->addRowAction($delete_action);
		
		$grid->setNoDataMessage($this->getTranslator()->trans('No posts was found.', array(), 'asf_doc_post'));
		
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
	public function editAction($id = null)
	{
		$securityContext = $this->get('security.context');
		
		if ( !is_null($id) ) {
			$original = $this->get('asf_doc.post.manager')->getRepository()->findOneBy(array('id' => $id));
			
			$post = clone $original;
			$this->get('asf_doc.post.manager')->getEntityManager()->detach($post);
			if ( is_null($original->getOriginal()) )
				$post->setOriginal($original);
			else
				$post->setOriginal($original->getOriginal());
			
			if (false === $securityContext->isGranted('EDIT', $post))
				throw new AccessDeniedException();
			$success_message = $this->getTranslator()->trans('Updated successfully', array(), 'asf_doc_post');
			
		} else {
			$post = $this->get('asf_doc.post.manager')->createInstance();
			
			if ( true === $this->container->getParameter('asf_doc.supports.account') && true === $this->container->getParameter('asf_doc.supports.asf_user') ) {
				$author = $this->get('security.context')->getToken()->getUser();
				$post->setAuthor($author);
			}
			
			$post->setTitle($this->getTranslator()->trans('New post', array(), 'asf_doc_post'))->setSlug($this->getTranslator()->trans('new-post', array(), 'asf_doc_post'));
			$success_message = $this->get('translator')->trans('Created successfully', array(), 'asf_doc_post');
		}
		
		if ( is_null($post) )
			throw new \Exception($this->getTranslator()->trans('An error occurs when generating or getting the post', array(), 'asf_doc_post'));

		$form = $this->get('asf_doc.form.post')->setData($post);
		$formHandler = $this->get('asf_doc.form.post.handler');
		
		if ( true === $formHandler->process() ) {
			try {
				$update_acl = false;
					
				if ( is_null($post->getId()) ) {
					$this->get('asf_doc.post.manager')->getEntityManager()->persist($post);
					$update_acl = true;
				}
				$this->get('asf_doc.post.manager')->getEntityManager()->flush();
					
				if ( true === $update_acl ) {
					$object_identity = ObjectIdentity::fromDomainObject($post);
					$acl = $this->get('security.acl.provider')->createAcl($object_identity);
				
					$security_identity = UserSecurityIdentity::fromAccount($post->getAuthor());
				
					$acl->insertObjectAce($security_identity, MaskBuilder::MASK_OWNER);
					$this->get('security.acl.provider')->updateAcl($acl);
				}
				$this->get('asf_layout.flash_message')->success($success_message);
				return $this->redirect($this->get('router')->generate('asf_doc_post_edit', array('id' => $post->getId())));
			} catch (\Exception $e) {
				$this->get('asf_layout.flash_message')->danger($e->getMeesage());
			}
		}
		
		return $this->render('ASFDocumentBundle:Post:edit.html.twig', array('post' => $post, 
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
		$securityContext = $this->get('security.context');
		$post = $this->get('asf_doc.post.manager')->getRepository()->findOneBy(array('id' => $id));
		if (false === $securityContext->isGranted('DELETE', $post))
			throw new AccessDeniedException();
		
		try {
			$this->get('asf_doc.post.manager')->getEntityManager()->remove($post);
			$this->get('asf_doc.post.manager')->getEntityManager()->flush();
			
			$this->get('asf_layout.flash_message')->success($this->getTranslator()->trans('The post "%name%" successfully deleted', array('%name%' => $post->getTitle()), 'asf_doc_post'));
			
		} catch (\Exception $e) {
			$this->get('asf_layout.flash_message')->danger($e->getMessage());
		}
		
		return $this->redirect($this->get('router')->generate('asf_doc_post_list'));
	}
}
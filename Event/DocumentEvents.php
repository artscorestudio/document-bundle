<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\DocumentBundle\Event;

/**
 * Document Events
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
final class DocumentEvents
{
	/**
	 * Page Edit Success if form is submitted without errors
	 *  
	 * @var string
	 */
	const PAGE_EDIT_SUCCESS = 'asf_document.page.edit.success';
	
	/**
	 * Post Edit Success if form is submitted without errors
	 *  
	 * @var string
	 */
	const POST_EDIT_SUCCESS = 'asf_document.post.edit.success';
}
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

use Symfony\Component\EventDispatcher\Event;

use APY\DataGridBundle\Grid\Grid;

/**
 * Post Event
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class PostGridEvent extends Event
{
	/**
	 * @var Grid
	 */
	protected $grid;
	
	/**
	 * @var mixed
	 */
	protected $source;
	
	/**
	 * @param Grid  $grid
	 * @param mixed $source
	 */
	public function __construct(Grid $grid, $source)
	{
		$this->grid = $grid;
		$this->source = $source;
	}
	
	/**
	 * @return mixed
	 */
	public function getSource()
	{
		return $this->source;
	}
	
	/**
	 * @return \APY\DataGridBundle\Grid\Grid
	 */
	public function getGrid()
	{
		return $this->grid;
	}
}
<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ConsoleController extends AbstractActionController
{
	private $factualService;
	
	public function __construct($factualService){
		$this->factualService = $factualService;
	}
    
    public function getDataAction()
    {
    	$request = $this->getRequest();
    	
    	$offset   = $request->getParam('offset', false);
    	$count     = $request->getParam('count');
    	
    	var_dump($this->factualService->getRemoteCategories());
    }
    
    public function getCategoriesAction()
    {
    	var_dump($this->factualService->getRemoteCategories());
    }
    
    public function getTraitsAction()
    {
    	var_dump($this->factualService->getRemoteTraits());
    }
}

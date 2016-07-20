<?php
/**
 * @author Oleksiy Perepelytsya
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ConsoleController extends AbstractActionController
{
	private $factualService;
	
	/**
	 * 
	 * @param Application/Controller/FactualAPIService
	 */
	public function __construct($factualService){
		$this->factualService = $factualService;
	}
    
	/**
	 * @return array
	 */
    public function getDataAction()
    {
    	$request = $this->getRequest();
    	
    	$offset   = $request->getParam('offset', false);
    	$count     = $request->getParam('count');
    	
    	var_dump($this->factualService->getRemoteCategories());
    }
    
    /**
     * @return array[]
     */
    public function getCategoriesAction()
    {
    	var_dump($this->factualService->getRemoteCategories());
    }
    
    /**
     * @return array[]
     */
    public function getTraitsAction()
    {
    	var_dump($this->factualService->getRemoteTraits());
    }
}

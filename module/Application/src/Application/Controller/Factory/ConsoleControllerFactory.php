<?php 

namespace Application\Controller\Factory;

use Application\Controller\ConsoleController;
 
class ConsoleControllerFactory
{
	/**
	 * @param ControllerManager
	 * 
	 * @return ConsoleController
	 */
    public function __invoke($controllerManager){
        $serviceManager = $controllerManager->getServiceLocator();
        $factualService = $serviceManager->get('Application\Service\APIServiceInterface');
        $factualService->initConnection();

        return new ConsoleController($factualService);
    }
}
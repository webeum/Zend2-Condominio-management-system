<?php

namespace Condominium;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Condominium\Model\Condominium;
use Condominium\Model\CondominiumTable;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Common' => __DIR__ . '/../../vendor/Common',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Condominium\Model\CondominiumTable' =>  function($sm) {
                    $tableGateway = $sm->get('CondominiumTableGateway');
                    $table = new CondominiumTable($tableGateway);
                    return $table;
                },
                'CondominiumTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Condominium());
                    return new TableGateway('condominium', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}

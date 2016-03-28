<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Administrator\Controller\Administrator' => 'Administrator\Controller\AdministratorController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'administrator' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/administrator[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Administrator\Controller\Administrator',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),


    'view_manager' => array(
        'template_path_stack' => array(
            'administrator' => __DIR__ . '/../view',
        ),
    ),
);

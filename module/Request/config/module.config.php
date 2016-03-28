<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Request\Controller\Request' => 'Request\Controller\RequestController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'request' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/request[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Request\Controller\Request',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),


    'view_manager' => array(
        'template_path_stack' => array(
            'request' => __DIR__ . '/../view',
        ),
    ),
);

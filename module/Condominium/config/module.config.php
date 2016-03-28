<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Condominium\Controller\Condominium' => 'Condominium\Controller\CondominiumController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'condominium' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/condominium[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Condominium\Controller\Condominium',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),


    'view_manager' => array(
        'template_path_stack' => array(
            'condominium' => __DIR__ . '/../view',
        ),
    ),
);
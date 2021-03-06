<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Condomino\Controller\Condomino' => 'Condomino\Controller\CondominoController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'condomino' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/condomino[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Condomino\Controller\Condomino',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),


    'view_manager' => array(
        'template_path_stack' => array(
            'condomino' => __DIR__ . '/../view',
        ),
    ),
);

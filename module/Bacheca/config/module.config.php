<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Bacheca\Controller\Bacheca' => 'Bacheca\Controller\BachecaController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'bacheca' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/bacheca[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Bacheca\Controller\Bacheca',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),


    'view_manager' => array(
        'template_path_stack' => array(
            'bacheca' => __DIR__ . '/../view',
        ),
    ),
);

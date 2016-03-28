<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Supplier\Controller\Supplier' => 'Supplier\Controller\SupplierController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'supplier' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/supplier[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Supplier\Controller\Supplier',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),


    'view_manager' => array(
        'template_path_stack' => array(
            'supplier' => __DIR__ . '/../view',
        ),
    ),
);

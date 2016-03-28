<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Document\Controller\Document' => 'Document\Controller\DocumentController',
        ),
    ),


    'router' => array(
        'routes' => array(
            'document' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/document[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Document\Controller\Document',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'document' => __DIR__ . '/../view',
        ),
    ),
);

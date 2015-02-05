<?php
return array(
    'fake' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/product',
            'defaults' => array(
            ),
        ),
    ),
    'fake-category' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/category',
            'defaults' => array(
            ),
        ),
    ),
    'fake-client' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/client',
            'defaults' => array(
            ),
        ),
    ),
    'fake-realization' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/realization',
            'defaults' => array(
            ),
        ),
    ),

    'product-list' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/product',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Product',
                'action'     => 'productList',
            ),
        ),
    ),

    'realization-list' => array(
        'type'    => 'Segment',
        'may_terminate' => true,
        'options' => array(
            'route'    => '/cms-ir/realization',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Realization',
                'action'     => 'realizationList',
            ),
        ),
        'child_routes' => array(
            'create-realization' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Realization',
                        'action' => 'createRealization',
                    ),
                ),
            ),
            'edit-realization' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:realization_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Realization',
                        'action' => 'editRealization',
                    ),
                    'constraints' => array(
                        'realization_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete-realization' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:realization_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Realization',
                        'action' => 'deleteRealization',
                    ),
                    'constraints' => array(
                        'realization_id' => '[0-9]+'
                    ),
                ),
            ),
        ),
    ),

    'category-list' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/category',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Category',
                'action'     => 'categoryList',
            ),
        ),
        'child_routes' => array(
            'create-category' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Category',
                        'action' => 'createCategory',
                    ),
                ),
            ),
            'edit-category' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:category_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Category',
                        'action' => 'editCategory',
                    ),
                    'constraints' => array(
                        'category_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete-category' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:category_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Category',
                        'action' => 'deleteCategory',
                    ),
                    'constraints' => array(
                        'category_id' => '[0-9]+'
                    ),
                ),
            ),
        ),
    ),

    'client-list' => array(
        'may_terminate' => true,
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/client',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Client',
                'action'     => 'clientList',
            ),
        ),
        'child_routes' => array(
            'create-client' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/create',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Client',
                        'action' => 'createClient',
                    ),
                ),
            ),
            'edit-client' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/edit/:client_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Client',
                        'action' => 'editClient',
                    ),
                    'constraints' => array(
                        'client_id' => '[0-9]+'
                    ),
                ),
            ),
            'delete-client' => array(
                'may_terminate' => true,
                'type' => 'Segment',
                'options' => array(
                    'route' => '/delete/:client_id',
                    'defaults' => array(
                        'module' => 'CmsIr\Product',
                        'controller' => 'CmsIr\Product\Controller\Client',
                        'action' => 'deleteClient',
                    ),
                    'constraints' => array(
                        'client_id' => '[0-9]+'
                    ),
                ),
            ),
        ),
    ),

    'upload-client' => array(
        'type'    => 'Segment',
        'options' => array(
            'route'    => '/cms-ir/client/upload',
            'defaults' => array(
                'module' => 'CmsIr\Product',
                'controller' => 'CmsIr\Product\Controller\Client',
                'action'     => 'upload',
            ),
            'constraints' => array(
            ),
        ),
    ),
);
<?php

return array(
    'router' => array(
        'routes' =>  include __DIR__ . '/routing.config.php',
    ),
    'controllers' => array(
        'invokables' => array(
            'Page\Controller\Page' => 'Page\Controller\PageController'
        ),
    ),
    'view_manager' => array(
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/home' => __DIR__ . '/../view/layout/home.phtml',
            'partial/layout/header' => __DIR__ . '/../view/partial/layout/header.phtml',
            'partial/layout/footer' => __DIR__ . '/../view/partial/layout/footer.phtml',

            'partial/page/products' => __DIR__ . '/../view/partial/page/products.phtml',
            'partial/page/single-product' => __DIR__ . '/../view/partial/page/single-product.phtml',
            'partial/page/about' => __DIR__ . '/../view/partial/page/about.phtml',
            'partial/page/contact' => __DIR__ . '/../view/partial/page/contact.phtml',
            'partial/page/single-post' => __DIR__ . '/../view/partial/page/single-post.phtml',
            'partial/page/posts' => __DIR__ . '/../view/partial/page/posts.phtml',
            'partial/page/post' => __DIR__ . '/../view/partial/page/post.phtml',

        ),
        'template_path_stack' => array(
            'page_home_site' => __DIR__ . '/../view'
        ),
        'display_exceptions' => true,
    ),
    'view_helpers' => array(
        'invokables'=> array(
            'menuHelper' => 'CmsIr\Menu\View\Helper\MenuHelper',
        ),
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),

);

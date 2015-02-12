<?php

return array(
    'home' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route'    => '/',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'home',
            ),
        ),
    ),
    'viewPage' => array(
        'type' => 'Zend\Mvc\Router\Http\Segment',
        'options' => array(
            'route'    => '/strona/:slug[/:post][/strona/:number]',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'viewPage',
            ),
        ),
    ),
    'viewNews' => array(
        'type' => 'Zend\Mvc\Router\Http\Segment',
        'options' => array(
            'route'    => '/strona/aktualnosci[/strona/:number]',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'viewNews',
            ),
            'constraints' => array(
                'number' => '[0-9_-]+'
            ),
        ),
    ),
    'oneNews' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/strona/aktualnosci/:slug',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'oneNews',
            ),
            'constraints' => array(
                'slug' => '[a-zA-Z0-9_-]+'
            ),
        ),
    ),
    'product-description' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/product-description',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'productDescription',
            ),
        ),
    ),

    'contact-form' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/contact-form',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'contactForm',
            ),
        ),
    ),
    'captcha' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/captcha',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'captcha',
            ),
        ),
    ),
    'save-subscriber' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route'    => '/save-new-subscriber',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'saveSubscriberAjax',
            ),
        ),
    ),
    'newsletter-confirmation' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/newsletter-potwierdzenie/:code',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'confirmationNewsletter',
            ),
            'constraints' => array(
                'code' => '[a-zA-Z0-9_-]+'
            ),
        ),
    ),
);
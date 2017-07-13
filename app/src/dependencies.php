<?php
/**
 * Copyright © 2017 Toan Nguyen. All rights reserved.
 * See COPYING.txt for license details.
 */

// DIC configuration
$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// View
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig($c['settings']['view']['template_path'], $c['settings']['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $c['request']->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// CSRF guard
$container['csrf'] = function ($c) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });
    return $guard;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

//$container['TableRates\Generator\Controller\Index'] = function ($c) {
//    return new TableRates\Generator\Controller\Index($c['view'], $c['router'], $c['flash']);
//};
//$container['TableRates\Generator\Controller\Post'] = function ($c) {
//    return new TableRates\Generator\Controller\Post($c['view'], $c['router'], $c['flash']);
//};

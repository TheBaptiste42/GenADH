<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
	'security.firewalls' => array(
		'secured' => array(
			'pattern' => '^/',
			'anonymous' => true,
			'logout' => true,
			'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
			'users' => $app->share(function () use ($app) {
				return new GenADH\DAO\UserDAO($app['db']);
			}),
		),
	),
	'security.role_hierarchy' => array(
		'ROLE_ADMIN' => array('ROLE_USER'),
	),
	'security.access_rules' => array(
		array('^/logged', 'ROLE_USER'),
		array('^/logged/adm', 'ROLE_ADMIN'),
	)
));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());

// Register services.
$app['dao.adherent'] = $app->share(function ($app) {
    return new GenADH\DAO\AdherentDAO($app['db']);
});
$app['dao.user'] = $app->share(function ($app) {
    return new GenADH\DAO\UserDAO($app['db']);
});
$app['dao.groupe'] = $app->share(function ($app) {
    return new GenADH\DAO\AdhGroupeDAO($app['db']);
});
$app['dao.cotisation'] = $app->share(function ($app) {
    return new GenADH\DAO\CotisationDAO($app['db']);
});

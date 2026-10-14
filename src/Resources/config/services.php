<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->set('cache.easyadmin')
        ->private()
        ->parent('cache.system')
        ->tag('cache.pool');

    $services->set('easyadmin.command.make_admin_migration', \EasyCorp\Bundle\EasyAdminBundle\Command\MakeAdminMigrationCommand::class)
        ->public()
        ->args([
            service('easyadmin.config.manager'),
            '%kernel.project_dir%',
        ])
        ->tag('console.command', ['command' => 'make:admin:migration']);

    $services->set('easyadmin.config.manager', \EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigManager::class)
        ->public()
        ->args([
            '%easyadmin.config%',
            '%kernel.debug%',
            service('property_accessor'),
            service('cache.easyadmin'),
        ]);

    $services->set('easyadmin.query_builder', \EasyCorp\Bundle\EasyAdminBundle\Search\QueryBuilder::class)
        ->public()
        ->args([service('doctrine')]);

    $services->set('easyadmin.finder', \EasyCorp\Bundle\EasyAdminBundle\Search\Finder::class)
        ->private()
        ->args([
            service('easyadmin.query_builder'),
            service('easyadmin.paginator'),
        ]);

    $services->set('easyadmin.autocomplete', \EasyCorp\Bundle\EasyAdminBundle\Search\Autocomplete::class)
        ->public()
        ->args([
            service('easyadmin.config.manager'),
            service('easyadmin.finder'),
            service('property_accessor'),
        ]);

    $services->set('easyadmin.paginator', \EasyCorp\Bundle\EasyAdminBundle\Search\Paginator::class)
        ->public();

    $services->set('easyadmin.router', \EasyCorp\Bundle\EasyAdminBundle\Router\EasyAdminRouter::class)
        ->public()
        ->args([
            service('easyadmin.config.manager'),
            service('router'),
            service('property_accessor'),
            service('request_stack')->nullOnInvalid(),
        ]);

    $services->set('easyadmin.twig.extension', \EasyCorp\Bundle\EasyAdminBundle\Twig\EasyAdminTwigExtension::class)
        ->private()
        ->args([
            service('easyadmin.config.manager'),
            service('property_accessor'),
            service('easyadmin.router'),
            '%kernel.debug%',
            service('security.logout_url_generator')->nullOnInvalid(),
            service('translator')->nullOnInvalid(),
            service('easyadmin.security.authorization_checker'),
        ])
        ->tag('twig.extension');

    $services->set('easyadmin.security.authorization_checker', \EasyCorp\Bundle\EasyAdminBundle\Security\AuthorizationChecker::class)
        ->public()
        ->args([service('security.authorization_checker')->nullOnInvalid()]);

    $services->set('easyadmin.listener.controller', \EasyCorp\Bundle\EasyAdminBundle\EventListener\ControllerListener::class)
        ->public()
        ->args([
            service('easyadmin.config.manager'),
            service('controller_resolver'),
        ])
        ->tag('kernel.event_listener', ['event' => 'kernel.controller', 'method' => 'onKernelController']);

    $services->set('easyadmin.listener.exception', \EasyCorp\Bundle\EasyAdminBundle\EventListener\ExceptionListener::class)
        ->public()
        ->args([
            service('twig'),
            '%easyadmin.config%',
        ])
        ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'method' => 'onKernelException', 'priority' => -64]);

    $services->set('easyadmin.listener.request_post_initialize', \EasyCorp\Bundle\EasyAdminBundle\EventListener\RequestPostInitializeListener::class)
        ->public()
        ->args([
            service('doctrine'),
            service('request_stack')->nullOnInvalid(),
        ])
        ->tag('kernel.event_listener', ['event' => 'easy_admin.post_initialize', 'method' => 'initializeRequest']);

    $services->set('easyadmin.data_collector', \EasyCorp\Bundle\EasyAdminBundle\DataCollector\EasyAdminDataCollector::class)
        ->private()
        ->args([service('easyadmin.config.manager')])
        ->tag('data_collector', ['template' => '@EasyAdmin/data_collector/easyadmin.html.twig', 'id' => 'easyadmin']);

    $services->set('easyadmin.configuration.normalizer_config_pass', \EasyCorp\Bundle\EasyAdminBundle\Configuration\NormalizerConfigPass::class)
        ->private()
        ->args([
            service('service_container'),
            service('easyadmin.filter.registry'),
        ])
        ->tag('easyadmin.config_pass', ['priority' => 90]);

    $services->set('easyadmin.configuration.design_config_pass', \EasyCorp\Bundle\EasyAdminBundle\Configuration\DesignConfigPass::class)
        ->private()
        ->args(['%kernel.default_locale%'])
        ->tag('easyadmin.config_pass', ['priority' => 80]);

    $services->set('easyadmin.configuration.menu_config_pass', \EasyCorp\Bundle\EasyAdminBundle\Configuration\MenuConfigPass::class)
        ->private()
        ->tag('easyadmin.config_pass', ['priority' => 70]);

    $services->set('easyadmin.configuration.action_config_pass', \EasyCorp\Bundle\EasyAdminBundle\Configuration\ActionConfigPass::class)
        ->private()
        ->tag('easyadmin.config_pass', ['priority' => 60]);

    $services->set('easyadmin.configuration.metadata_config_pass', \EasyCorp\Bundle\EasyAdminBundle\Configuration\MetadataConfigPass::class)
        ->private()
        ->args([service('doctrine')])
        ->tag('easyadmin.config_pass', ['priority' => 50]);

    $services->set('easyadmin.configuration.property_config_pass', \EasyCorp\Bundle\EasyAdminBundle\Configuration\PropertyConfigPass::class)
        ->private()
        ->args([
            service('form.registry'),
            service('easyadmin.filter.registry'),
        ])
        ->tag('easyadmin.config_pass', ['priority' => 40]);

    $services->set('easyadmin.configuration.view_config_pass', \EasyCorp\Bundle\EasyAdminBundle\Configuration\ViewConfigPass::class)
        ->private()
        ->tag('easyadmin.config_pass', ['priority' => 30]);

    $services->set('easyadmin.configuration.template_config_pass', \EasyCorp\Bundle\EasyAdminBundle\Configuration\TemplateConfigPass::class)
        ->private()
        ->args([service('twig.loader.filesystem')])
        ->tag('easyadmin.config_pass', ['priority' => 20]);

    $services->set('easyadmin.configuration.default_config_pass', \EasyCorp\Bundle\EasyAdminBundle\Configuration\DefaultConfigPass::class)
        ->private()
        ->tag('easyadmin.config_pass', ['priority' => 10]);

    $services->alias('easyadmin.property_accessor', 'property_accessor')
        ->public();

    $services->set(\EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController::class, \EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController::class)
        ->public()
        ->autowire()
        ->tag('container.service_subscriber');

    // aliases needed for the service locator of the AbstractAdminController
    $services->alias(\EasyCorp\Bundle\EasyAdminBundle\Configuration\ConfigManager::class, 'easyadmin.config.manager');
    $services->alias(\EasyCorp\Bundle\EasyAdminBundle\Search\QueryBuilder::class, 'easyadmin.query_builder');
    $services->alias(\EasyCorp\Bundle\EasyAdminBundle\Search\Paginator::class, 'easyadmin.paginator');
    $services->alias(\EasyCorp\Bundle\EasyAdminBundle\Search\Autocomplete::class, 'easyadmin.autocomplete');
    $services->alias(\EasyCorp\Bundle\EasyAdminBundle\Security\AuthorizationChecker::class, 'easyadmin.security.authorization_checker');
};

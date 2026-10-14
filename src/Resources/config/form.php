<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->set('easyadmin.form.type', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminFormType::class)
        ->args([
            service('easyadmin.config.manager'),
            [],
            service('easyadmin.security.authorization_checker'),
        ])
        ->tag('form.type', ['alias' => 'easyadmin']);

    $services->set('easyadmin.form.type.fileupload', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType::class)
        ->args(['%kernel.project_dir%'])
        ->tag('form.type');

    $services->set('easyadmin.form.type.filters', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminFiltersFormType::class)
        ->args([
            service('easyadmin.config.manager'),
            [],
        ])
        ->tag('form.type', ['alias' => 'easyadmin_filters']);

    $services->set('easyadmin.form.type.autocomplete', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminAutocompleteType::class)
        ->args([service('easyadmin.config.manager')])
        ->tag('form.type', ['alias' => 'easyadmin_autocomplete']);

    $services->set('easyadmin.form.type.batch', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminBatchFormType::class)
        ->args([service('easyadmin.config.manager')])
        ->tag('form.type', ['alias' => 'easyadmin_batch']);

    $services->set('easyadmin.form.type.divider', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminDividerType::class)
        ->tag('form.type', ['alias' => 'easyadmin_divider']);

    $services->set('easyadmin.form.type.section', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminSectionType::class)
        ->tag('form.type', ['alias' => 'easyadmin_section']);

    $services->set('easyadmin.form.type.group', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminGroupType::class)
        ->tag('form.type', ['alias' => 'easyadmin_group']);

    $services->set('easyadmin.form.type.extension', \EasyCorp\Bundle\EasyAdminBundle\Form\Extension\EasyAdminExtension::class)
        ->args([service('request_stack')->nullOnInvalid()])
        ->tag('form.type_extension', ['alias' => 'form', 'extended_type' => \Symfony\Component\Form\Extension\Core\Type\FormType::class]);

    // Type Configurators
    $services->set('easyadmin.form.type.configurator.code_editor', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\CodeEditorTypeConfigurator::class)
        ->private()
        ->tag('easyadmin.form.type.configurator', ['priority' => 50]);

    $services->set('easyadmin.filter.type.configurator.choice', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\Configurator\ChoiceFilterTypeConfigurator::class)
        ->tag('easyadmin.form.type.configurator', ['priority' => 0]);

    $services->set('easyadmin.form.type.configurator.textarea', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\TextareaTypeConfigurator::class)
        ->private()
        ->tag('easyadmin.form.type.configurator', ['priority' => 40]);

    $services->set('easyadmin.form.type.configurator.autocomplete', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\AutocompleteTypeConfigurator::class)
        ->private()
        ->tag('easyadmin.form.type.configurator', ['priority' => 30]);

    $services->set('easyadmin.form.type.configurator.collection', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\CollectionTypeConfigurator::class)
        ->private()
        ->tag('easyadmin.form.type.configurator', ['priority' => 20]);

    $services->set('easyadmin.form.type.configurator.checkbox', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\CheckboxTypeConfigurator::class)
        ->private()
        ->tag('easyadmin.form.type.configurator', ['priority' => 10]);

    $services->set('easyadmin.form.type.configurator.type', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\TypeConfigurator::class)
        ->private()
        ->args([service('easyadmin.config.manager')])
        ->tag('easyadmin.form.type.configurator', ['priority' => 0]);

    $services->set('easyadmin.form.type.configurator.entity', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\EntityTypeConfigurator::class)
        ->private()
        ->tag('easyadmin.form.type.configurator', ['priority' => -20]);

    $services->set('easyadmin.form.type.configurator.ivory_ckeditor', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\IvoryCKEditorTypeConfigurator::class)
        ->private()
        ->tag('easyadmin.form.type.configurator', ['priority' => -130]);

    $services->set('easyadmin.form.type.configurator.fos_ckeditor', \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\FOSCKEditorTypeConfigurator::class)
        ->private()
        ->tag('easyadmin.form.type.configurator', ['priority' => -130]);

    // Type Configurators
    $services->set('easyadmin.filter.registry', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\FilterRegistry::class)
        ->public()
        ->args([
            [],
            iterator([]),
        ]);

    $services->alias(\EasyCorp\Bundle\EasyAdminBundle\Form\Filter\FilterRegistry::class, 'easyadmin.filter.registry')
        ->public();

    $services->set('easyadmin.filter.extension', \Symfony\Component\Form\Extension\DependencyInjection\DependencyInjectionExtension::class)
        ->args([
            '',
            [],
            iterator([]),
        ]);

    $services->set('easyadmin.filter.type_guesser.doctrine', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Guesser\DoctrineOrmFilterTypeGuesser::class)
        ->args([service('doctrine')])
        ->tag('easyadmin.filter.type_guesser');

    $services->set('easyadmin.form.guesser.missing_doctrine_orm_type_guesser', \EasyCorp\Bundle\EasyAdminBundle\Form\Guesser\MissingDoctrineOrmTypeGuesser::class)
        ->public()
        ->args([service('doctrine')])
        ->tag('form.type_guesser');

    $services->set('easyadmin.filter.type.array', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\ArrayFilterType::class)
        ->tag('easyadmin.filter.type', ['alias' => 'array']);

    $services->set('easyadmin.filter.type.boolean', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\BooleanFilterType::class)
        ->tag('easyadmin.filter.type', ['alias' => 'boolean']);

    $services->set('easyadmin.filter.type.comparison', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\ComparisonFilterType::class)
        ->tag('easyadmin.filter.type', ['alias' => 'comparison']);

    $services->set('easyadmin.filter.type.date', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\DateTimeFilterType::class)
        ->args([\Symfony\Component\Form\Extension\Core\Type\DateType::class])
        ->tag('easyadmin.filter.type', ['alias' => 'date']);

    $services->set('easyadmin.filter.type.dateinterval', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\ComparisonFilterType::class)
        ->args([
            \Symfony\Component\Form\Extension\Core\Type\DateIntervalType::class,
            [],
            '',
            ['type' => \datetime::class],
        ])
        ->tag('easyadmin.filter.type', ['alias' => \dateinterval::class]);

    $services->set('easyadmin.filter.type.datetime', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\DateTimeFilterType::class)
        ->tag('easyadmin.filter.type', ['alias' => \datetime::class]);

    $services->set('easyadmin.filter.type.decimal', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\NumericFilterType::class)
        ->args([
            \Symfony\Component\Form\Extension\Core\Type\NumberType::class,
            ['input' => 'string'],
        ])
        ->tag('easyadmin.filter.type', ['alias' => 'decimal']);

    $services->set('easyadmin.filter.type.choice', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\ChoiceFilterType::class)
        ->tag('easyadmin.filter.type', ['alias' => 'choice']);

    $services->set('easyadmin.filter.type.entity', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\EntityFilterType::class)
        ->tag('easyadmin.filter.type', ['alias' => 'entity']);

    $services->set('easyadmin.filter.type.float', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\NumericFilterType::class)
        ->tag('easyadmin.filter.type', ['alias' => 'float']);

    $services->set('easyadmin.filter.type.integer', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\NumericFilterType::class)
        ->args([\Symfony\Component\Form\Extension\Core\Type\IntegerType::class])
        ->tag('easyadmin.filter.type', ['alias' => 'integer']);

    $services->set('easyadmin.filter.type.text', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\TextFilterType::class)
        ->tag('easyadmin.filter.type', ['alias' => 'text']);

    $services->set('easyadmin.filter.type.textarea', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\TextFilterType::class)
        ->args([\Symfony\Component\Form\Extension\Core\Type\TextareaType::class])
        ->tag('easyadmin.filter.type', ['alias' => 'textarea']);

    $services->set('easyadmin.filter.type.time', \EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\DateTimeFilterType::class)
        ->args([\Symfony\Component\Form\Extension\Core\Type\TimeType::class])
        ->tag('easyadmin.filter.type', ['alias' => 'time']);
};

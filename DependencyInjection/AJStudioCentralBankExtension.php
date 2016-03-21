<?php

namespace AJStudio\CentralBankBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AJStudioCentralBankExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration( $configuration, $configs );

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('ajstudio_central_bank.url', $processedConfig['url']);
        $container->setParameter('ajstudio_central_bank.currencies', $processedConfig['currencies']);
        $container->setParameter('ajstudio_central_bank.allow_db_history', $processedConfig['currencies']);
        $container->setParameter('ajstudio_central_bank.currency_entity', $processedConfig['currencies']);
        $container->setParameter('ajstudio_central_bank.currency_has_value_entity', $processedConfig['currencies']);

        $geoServiceDefinition = $container->getDefinition('ajstudio.central_bank');
        $geoServiceDefinition->addMethodCall('setConfig', [ $processedConfig ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace() {
        return 'ajstudio_central_bank';
    }
}

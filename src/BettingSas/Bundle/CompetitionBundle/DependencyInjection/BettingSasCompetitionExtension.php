<?php

namespace BettingSas\Bundle\CompetitionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * BettingSasCompetitionBundleExtension.
 *
 * @author jobou
 */
class BettingSasCompetitionExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), $configs);

        $this->loadConfiguration($config, $container);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function loadConfiguration(array $configs, ContainerBuilder $container)
    {
        $container->setParameter('betting_sas.event_mapping', $configs['mapping_events']);
    }
}

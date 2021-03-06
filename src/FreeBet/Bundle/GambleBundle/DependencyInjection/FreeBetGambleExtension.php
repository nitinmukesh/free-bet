<?php

namespace FreeBet\Bundle\GambleBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * FreeBetCompetitionBundleExtension.
 *
 * @author jobou
 */
class FreeBetGambleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), $configs);
        $container->setParameter('free_bet.validation_group', $config['validation_group']);

        $gambleConfig = array();

        foreach ($container->getParameter('kernel.bundles') as $bundle) {
            $reflection = new \ReflectionClass($bundle);
            $file = dirname($reflection->getFilename()) . '/Resources/config/gamble_types.yml';
            if (is_file($file)) {
                $bundleConfig = Yaml::parse(realpath($file));
                $gambleConfig = array_merge($gambleConfig, $bundleConfig);
            }
        }

        $container->setParameter('free_bet.gamble.configuration', $gambleConfig);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('gamble_validators.yml');
        $loader->load('gamble_processors.yml');
    }
}

<?php

declare(strict_types = 1);

namespace Consistence\JmsSerializer\SymfonyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ConsistenceJmsSerializerExtension extends \Symfony\Component\HttpKernel\DependencyInjection\Extension
{

	public const ALIAS = 'consistence_jms_serializer';

	/**
	 * @param mixed[][] $configs
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 */
	public function load(array $configs, ContainerBuilder $container): void
	{
		$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
		$loader->load('services.yaml');
	}

	public function getAlias(): string
	{
		return self::ALIAS;
	}

}

<?php

namespace Consistence\JmsSerializer\SymfonyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConsistenceJmsSerializerExtension extends \Symfony\Component\HttpKernel\DependencyInjection\Extension
{

	const ALIAS = 'consistence_jms_serializer';

	/**
	 * @param mixed[][] $configs
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		// ...
	}

	/**
	 * @return string
	 */
	public function getAlias()
	{
		return self::ALIAS;
	}

}

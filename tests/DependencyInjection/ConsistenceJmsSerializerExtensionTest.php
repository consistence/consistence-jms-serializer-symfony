<?php

declare(strict_types = 1);

namespace Consistence\JmsSerializer\SymfonyBundle\DependencyInjection;

use Consistence\JmsSerializer\Enum\EnumSerializerHandler;

class ConsistenceJmsSerializerExtensionTest extends \Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase
{

	/**
	 * @return \Symfony\Component\DependencyInjection\Extension\ExtensionInterface[]
	 */
	protected function getContainerExtensions(): array
	{
		return [
			new ConsistenceJmsSerializerExtension(),
		];
	}

	public function testRegisterSerializerHandler()
	{
		$this->load();

		$this->assertContainerBuilderHasService(
			'consistence.jms_serializer.enum.enum_serializer_handler',
			EnumSerializerHandler::class
		);
		$this->assertContainerBuilderHasServiceDefinitionWithTag(
			'consistence.jms_serializer.enum.enum_serializer_handler',
			'jms_serializer.subscribing_handler'
		);

		$this->compile();
	}

}

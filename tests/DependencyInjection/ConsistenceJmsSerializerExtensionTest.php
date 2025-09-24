<?php

declare(strict_types = 1);

namespace Consistence\JmsSerializer\SymfonyBundle\DependencyInjection;

use Consistence\JmsSerializer\Enum\EnumSerializerHandler;
use PHPUnit\Framework\Assert;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ConsistenceJmsSerializerExtensionTest extends \PHPUnit\Framework\TestCase
{

	public function testRegisterSerializerHandler(): void
	{
		$container = self::createContainer();
		$container->registerExtension(new ConsistenceJmsSerializerExtension());

		foreach ($container->getExtensions() as $extension) {
			$extension->load([], $container);
		}

		$serviceId = 'consistence.jms_serializer.enum.enum_serializer_handler';
		self::assertContainerHasService($container, $serviceId);
		self::assertContainerServiceIsOfType($container, $serviceId, EnumSerializerHandler::class);
		self::assertContainerServiceHasTagWithAttributes(
			$container,
			$serviceId,
			'jms_serializer.subscribing_handler',
			[]
		);

		$container->compile();
	}

	private static function createContainer(): ContainerBuilder
	{
		$container = new ContainerBuilder(new ParameterBag([]));
		$container->getCompilerPassConfig()->setOptimizationPasses([]);
		$container->getCompilerPassConfig()->setRemovingPasses([]);
		$container->getCompilerPassConfig()->setAfterRemovingPasses([]);

		return $container;
	}

	private static function assertContainerHasService(ContainerBuilder $container, string $serviceId): void
	{
		Assert::assertTrue(
			$container->has($serviceId),
			sprintf('Container is missing required service `%s`.', $serviceId)
		);
	}

	private static function assertContainerServiceIsOfType(
		ContainerBuilder $container,
		string $serviceId,
		string $expectedClassString
	): void
	{
		$serviceDefinition = $container->findDefinition($serviceId);

		Assert::assertSame(
			$expectedClassString,
			$container->getParameterBag()->resolveValue($serviceDefinition->getClass())
		);
	}

	private static function assertContainerServiceHasTagWithAttributes(
		ContainerBuilder $container,
		string $serviceId,
		string $tagName
	): void
	{
		$tagExists = false;
		$serviceDefinition = $container->findDefinition($serviceId);

		foreach ($serviceDefinition->getTags() as $name => $tagsAttributes) {
			if ($name !== $tagName) {
				continue;
			}

			$tagExists = true;
		}

		if (!$tagExists) {
			Assert::fail(sprintf('Service `%s` does not have any tag `%s`.', $serviceId, $tagName));
		}
	}

}

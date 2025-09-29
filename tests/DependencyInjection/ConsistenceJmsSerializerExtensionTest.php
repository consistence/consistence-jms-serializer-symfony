<?php

declare(strict_types = 1);

namespace Consistence\JmsSerializer\SymfonyBundle\DependencyInjection;

use Consistence\JmsSerializer\Enum\EnumSerializerHandler;
use PHPUnit\Framework\Assert;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class ConsistenceJmsSerializerExtensionTest extends \PHPUnit\Framework\TestCase
{

	public function testRegisterSerializerHandler(): void
	{
		$container = self::createContainer();
		$container->registerExtension(new ConsistenceJmsSerializerExtension());

		self::loadRegisteredExtension($container);

		$serviceId = 'consistence.jms_serializer.enum.enum_serializer_handler';
		self::assertContainerHasService($container, $serviceId);
		self::assertContainerServiceIsOfType($container, $serviceId, EnumSerializerHandler::class);
		self::assertContainerServiceHasTag(
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

	public static function assertContainerHasService(
		ContainerBuilder $container,
		string $serviceId
	): void
	{
		Assert::assertTrue(
			$container->has($serviceId),
			sprintf('Expecting the container to have service `%s`.', $serviceId)
		);
	}

	public static function assertContainerServiceIsOfType(
		ContainerBuilder $container,
		string $serviceId,
		string $expectedClassString
	): void
	{
		$serviceDefinition = $container->findDefinition($serviceId);

		Assert::assertSame(
			$expectedClassString,
			$container->getParameterBag()->resolveValue($serviceDefinition->getClass()),
			sprintf('Expecting the service `%s` to be of type `%s`.', $serviceId, $expectedClassString)
		);
	}

	private static function assertContainerServiceHasTag(
		ContainerBuilder $container,
		string $serviceId,
		string $tagName
	): void
	{
		$serviceDefinition = $container->findDefinition($serviceId);

		Assert::assertTrue(
			$serviceDefinition->hasTag($tagName),
			sprintf('Expecting the service `%s` to have tag `%s`.', $serviceId, $tagName)
		);
	}

	/**
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @param mixed[][]|array $configuration
	 */
	public static function loadRegisteredExtension(
		ContainerBuilder $container,
		array $configuration = []
	): void
	{
		$registeredExtensionsCount = count($container->getExtensions());

		assert(
			$registeredExtensionsCount === 1,
			sprintf('There are %d extensions registered but one is expected', $registeredExtensionsCount)
		);

		self::loadRegisteredExtensionsUsingCommonConfiguration($container, $configuration);
	}

	/**
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @param mixed[][]|array $configuration
	 */
	private static function loadRegisteredExtensionsUsingCommonConfiguration(
		ContainerBuilder $container,
		array $configuration = []
	): void
	{
		foreach ($container->getExtensions() as $extension) {
			if ($extension instanceof PrependExtensionInterface) {
				$extension->prepend($container);
			}
		}

		foreach ($container->getExtensions() as $extension) {
			$extension->load([$configuration], $container);
		}
	}

}

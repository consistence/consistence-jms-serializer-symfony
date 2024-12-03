<?php

declare(strict_types = 1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

$config = $config->enableAnalysisOfUnusedDevDependencies();
$config = $config->addPathToScan(__DIR__, true);

// dependency from YAML configuration only
$config = $config->ignoreErrorsOnPackages([
	'consistence/consistence-jms-serializer', // using Consistence\JmsSerializer\Enum\EnumSerializerHandler
], [ErrorType::PROD_DEPENDENCY_ONLY_IN_DEV]);
$config = $config->ignoreErrorsOnPackages([
	'jms/serializer-bundle', // using jms_serializer.subscribing_handler event
], [ErrorType::UNUSED_DEPENDENCY]);

// opt-in Symfony functionality
$config = $config->ignoreErrorsOnPackages([
	'symfony/yaml',
], [ErrorType::UNUSED_DEPENDENCY]);

// tools
$config = $config->ignoreErrorsOnPackages([
	'consistence/coding-standard',
	'phing/phing',
	'phpunit/phpunit',
	'php-parallel-lint/php-console-highlighter',
	'php-parallel-lint/php-parallel-lint',
], [ErrorType::UNUSED_DEPENDENCY]);

return $config;

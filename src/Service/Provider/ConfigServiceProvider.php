<?php
declare(strict_types=1);
namespace App\Service\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class ConfigServiceProvider
 * @package App\Service\Provider
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * Configuration parameters defaults.
     *
     * @var array
     */
    private $options = [
        'debug'                      => false,
        'monolog.level'              => \Monolog\Logger::ERROR,

        'db.migrations.namespace'    => 'App\\Entity\\Migration',
        'db.migrations.directory'    => __DIR__ . '/../../../src/Entity/Migrations',
        'db.migrations.tableName'    => 'migration_version',
        'db.migrations.name'         => 'Database Migrations',
    ];

    /**
     * ConfigServiceProvider constructor.
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        $this->options = array_merge(
            $this->options,
            $config
        );
    }

    /**
     * Registers service
     *
     * @param Container $app
     */
    public function register(Container $app): void
    {
        foreach ($this->options as $setting => $value) {
            $app[$setting] = $value;
        }
    }
}

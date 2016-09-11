<?php
namespace App\Service\Provider;

use Ivoba\Silex\EnvProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class provide configuration for app.
 * @package App\Service\Provider
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * Dotenv options.
     *
     * @var array
     */
    private $options = [
        'debug'                      => [EnvProvider::CONFIG_KEY_DEFAULT => false, EnvProvider::CONFIG_KEY_CAST => EnvProvider::CAST_TYPE_BOOLEAN],
        'monolog.level'              => [EnvProvider::CONFIG_KEY_DEFAULT => \Monolog\Logger::ERROR, EnvProvider::CONFIG_KEY_CAST => EnvProvider::CAST_TYPE_INT],

        'db.driver'                  => [EnvProvider::CONFIG_KEY_DEFAULT => 'pdo_pgsql'],
        'db.host'                    => [EnvProvider::CONFIG_KEY_DEFAULT => 'localhost'],
        'db.user'                    => [EnvProvider::CONFIG_KEY_DEFAULT => 'postgres'],
        'db.password'                => [],
        'db.port'                    => [EnvProvider::CONFIG_KEY_DEFAULT => '5432'],
        'db.dbname'                  => [],
        'db.migrations.namespace'    => [EnvProvider::CONFIG_KEY_DEFAULT => 'App\\Entity\\Migration'],
        'db.migrations.directory'    => [EnvProvider::CONFIG_KEY_DEFAULT => __DIR__ . '/../../../src/Entity/Migrations'],
        'db.migrations.tableName'    => [EnvProvider::CONFIG_KEY_DEFAULT => 'migration_version'],
        'db.migrations.name'         => [EnvProvider::CONFIG_KEY_DEFAULT => 'Database Migrations'],
    ];
    /**
     * @var EnvProvider
     */
    protected $provider;

    /**
     * @var bool
     */
    protected $useDotEnv;

    /**
     * ConfigServiceProvider constructor.
     *
     * @param EnvProvider $provider
     * @param bool $useDotEnv
     */
    public function __construct(EnvProvider $provider, bool $useDotEnv = true)
    {
        $this->provider = $provider;
        $this->useDotEnv = $useDotEnv;
    }

    /**
     * Registers service
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        $envOptions = [
            'env.options' => [
                'prefix'     => 'app',
                'dotenv_dir' => __DIR__ . '/../../../',
                'var_config' => $this->options,
                'use_dotenv' => function () {
                    return $this->useDotEnv;
                },
            ],
        ];

        $app->register($this->provider, $envOptions);

        try {
            $app['env.load'];
        } catch (\InvalidArgumentException $e) {
            // cannot find .env file - np
        }
    }
}

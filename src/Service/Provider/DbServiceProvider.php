<?php

declare(strict_types=1);

namespace App\Service\Provider;

use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class provide ORM mechanism.
 */
class DbServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers service.
     *
     * @param Container $app
     */
    public function register(Container $app): void
    {
        $app->register(new \Silex\Provider\DoctrineServiceProvider(), [
            'db.options' => [
                'driver' => $app['db.driver'],
                'dbname' => $app['db.dbname'],
                'host' => $app['db.host'],
                'user' => $app['db.user'],
                'password' => $app['db.password'],
                'port' => $app['db.port'],
                'driverOptions' => [
                    \PDO::ATTR_EMULATE_PREPARES => true,
                ],
            ],
        ]);

        $app->register(new DoctrineOrmServiceProvider(), [
            'orm.proxies_dir' => __DIR__.'/../../../storage/proxies',
            'orm.em.options' => [
                'mappings' => [
                    [
                        'type' => 'annotation',
                        'namespace' => 'App\Entity',
                        'path' => __DIR__.'/../../../src',
                        'use_simple_annotation_reader' => false,
                    ],
                ],
            ],
        ]);

        \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([
            require __DIR__.'/../../../vendor/autoload.php',
            'loadClass',
        ]);
    }
}

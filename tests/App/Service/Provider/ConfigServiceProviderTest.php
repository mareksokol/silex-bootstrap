<?php

declare(strict_types=1);

namespace Tests\App\Service\Provider;

use App\Service\Provider\ConfigServiceProvider;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

/**
 * Class ConfigServiceProviderTest.
 *
 * @coversNothing
 */
class ConfigServiceProviderTest extends TestCase
{
    public function testCallRegisterMethodNumber(): void
    {
        $options = [
            'debug' => true,
            'monolog.level' => 200,
            'db.dbname' => 'test',
            'db.password' => 'postgres',
        ];

        $pimple = new Container();
        $provider = new ConfigServiceProvider($options);

        $provider->register($pimple);

        $this->assertTrue($pimple['debug']);
        $this->assertSame(\Monolog\Logger::INFO, $pimple['monolog.level']);
        $this->assertSame('postgres', $pimple['db.password']);
        $this->assertSame('test', $pimple['db.dbname']);
    }
}

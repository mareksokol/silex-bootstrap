<?php
namespace Tests\App\Service\Provider;

use App\Service\Provider\ConfigServiceProvider;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class ConfigServiceProviderTest extends TestCase
{
    public function testCallRegisterMethodNumber()
    {
        $options = [
            'debug'         => true,
            'monolog.level' => 200,
            'db.dbname'     => 'test',
            'db.password'   => 'postgres',
        ];

        $pimple = new Container();
        $provider = new ConfigServiceProvider($options);

        $provider->register($pimple);

        $this->assertTrue($pimple['debug']);
        $this->assertEquals(\Monolog\Logger::INFO, $pimple['monolog.level']);
        $this->assertEquals('postgres', $pimple['db.password']);
        $this->assertEquals('test', $pimple['db.dbname']);
    }
}

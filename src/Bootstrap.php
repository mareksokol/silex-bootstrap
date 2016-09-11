<?php
namespace App;

use Ivoba\Silex\EnvProvider;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Silex\Provider;

/**
 * Class Bootstrap.
 * @package App
 */
class Bootstrap
{
    /**
     * Registers application services.
     *
     * @param Application $app
     * @return Bootstrap
     */
    public function loadServices(Application $app): Bootstrap
    {
        $app->register(new Service\Provider\ConfigServiceProvider(new EnvProvider()));

        $app->register(new Provider\MonologServiceProvider(), [
            'monolog.name'    => 'app',
            'monolog.logfile' => __DIR__ . '/../storage/logs/app.log',
            'monolog.level'   => $app['monolog.level'],
        ]);

        $app->register(new Service\Provider\DbServiceProvider());
        $app->register(new Provider\ServiceControllerServiceProvider());
        $app->register(new Provider\ValidatorServiceProvider());

        return $this;
    }

    /**
     * Register console provider.
     *
     * @param Application $app
     * @return Bootstrap
     */
    public function loadConsoleServices(Application $app): Bootstrap
    {
        $app->register(new Service\Provider\ConsoleServiceProvider());

        return $this;
    }

    /**
     * Registers application controllers.
     *
     * @param Application $app
     * @return Bootstrap
     */
    public function loadControllers(Application $app): Bootstrap
    {
        $app['Controller.Default'] = function () use ($app) {
            return new Controller\Auth();
        };

        $this->routes($app);

        return $this;
    }

    /**
     * Setup error handler.
     *
     * @param Application $app
     * @return Bootstrap
     */
    public function errorHandler(Application $app): Bootstrap
    {
        $app->error(function (\Exception $e, Request $request, int $code) use ($app) {
            return $app['Controller.Default']->error($e->getMessage(), $code);
        });

        return $this;
    }

    /**
     * Setup application routes.
     *
     * @param Application $app
     */
    private function routes(Application $app)
    {
        $app->before(function (Request $request) {
            if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : []);
            }
        });

        $app->get('/', 'Controller.Default:indexAction');
    }
}
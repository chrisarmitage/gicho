<?php

namespace Gicho;

use Auryn\Injector;
use Dotenv\Dotenv;
use Gicho\Router\RpcRouter;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var ControllerResolver
     */
    protected $controllerResolver;

    /**
     * @var Injector
     */
    protected $container;

    protected $runListener = true;

    public function __construct($rootDirectory)
    {
        /**
         * Load the environment
         */
        $dotenv = Dotenv::createImmutable($rootDirectory);
        $dotenv->load();

        $container = new \Auryn\Injector();

        $container->share($container);

        $container->share(new RpcRouter(null));

        $container->alias(\Gicho\Router::class, RpcRouter::class);

        $router = $container->make(Router::class);
        $controllerResolver = $container->make(ControllerResolver::class);

        $this->router = $router;
        $this->controllerResolver = $controllerResolver;
        $this->container = $container;
    }

    public function run(): void
    {
        $request = Request::createFromGlobals();

        $route = $this->router->getRouteForUrl($request->getPathInfo(), $request->getMethod());

        $this->container->share($route);

        $controller = $this->controllerResolver->resolve($route->getControllerName());

        $controllerResponse = $controller->dispatch(...array_values($route->getParams()));

        if ($controllerResponse instanceof Response === false) {
            $response = new Response(
                json_encode($controllerResponse),
                Response::HTTP_OK,
                [
                    'content-type' => 'application/json',
                ]
            );
        } else {
            $response = $controllerResponse;
        }

        $response->prepare($request);

        $response->send();
    }

    public function console()
    {
        $args = $_SERVER['argv'];

        // Inline code
        $command = $args[1];

        $commandName = str_replace(
            ' ',
            '',
            ucwords(
                str_replace(
                    '-',
                    ' ',
                    strtolower($command)
                )
            )
        );

        $actual = $this->container->make('Application\\Console\\' . $commandName);

        $actual->execute();
    }

    public function attach()
    {
        /**
         * Activate pcntl signals, listen for TERM and INT, and set runListener to false if detected
         */
        pcntl_async_signals(true);
        pcntl_signal(SIGTERM, function () {
            $this->runListener = false;
            echo '[Requesting shutdown - TERM - ' . (new \DateTimeImmutable())->format('H:i:s') . ']';
        });
        pcntl_signal(SIGINT, function () {
            $this->runListener = false;
            echo '[Requesting shutdown - INT - ' . (new \DateTimeImmutable())->format('H:i:s') . ']';
        });

        $argv = $_SERVER['argv'];

        // strip the application name
        array_shift($argv);

        $tokens = $argv;

        /**
         * Parse options
         */
        $parsedTokens = [];
        // Work through all the tokens
        while (null !== $token = array_shift($tokens)) {
            if (str_starts_with($token, '--')) {
                // token is a long option
                $tokenName = substr($token, 2);
                $tokenValue = array_shift($tokens);
                $parsedTokens[$tokenName] = $tokenValue;
            }
        }

        /**
         * Inline listener
         */

        $listenerName = str_replace(
            ' ',
            '',
            ucwords(
                str_replace(
                    '-',
                    ' ',
                    strtolower($parsedTokens['listener'])
                )
            )
        );

        $actual = $this->container->make('Application\\Listener\\' . $listenerName);

        switch ($parsedTokens['connection']) {
            case 'redis:first':
                $redis = new \Redis();
                $redis->connect('redis', 6379);
                echo 'Processing...' . PHP_EOL;

                while (true) {
                    $data = $redis->blpop('test_pipeline.first', 30);
                    // echo 'Got data...' . PHP_EOL;
                    if (count($data) !== 0) {
                        $actual->execute(json_decode($data[1]));
                    }
                }
                break;
            case 'redis:second':
                $redis = new \Redis();
                $redis->connect('redis', 6379);
                echo 'Processing...' . PHP_EOL;

                while (true) {
                    $data = $redis->blpop('test_pipeline.second', 30);
                    // echo 'Got data...' . PHP_EOL;
                    if (count($data) !== 0) {
                        $actual->execute(json_decode($data[1]));
                    }
                }
                break;
            case 'amqp':
                $connection = new AMQPStreamConnection(
                    $_ENV['AMQP_HOST'],
                    $_ENV['AMQP_PORT'],
                    $_ENV['AMQP_USER'],
                    $_ENV['AMQP_PASSWORD'],
                    $_ENV['AMQP_VHOST'],
                );
                $channel = $connection->channel();
                $channel->basic_qos(null, 1, null);
                $channel->queue_declare('amqp_test', false, false, false, false);

                $channel->basic_consume('amqp_test', '', false, false, false, false, function($message) use ($actual) {
                    $actual->execute(json_decode($message->body));

                    $message->ack();
                });

                while ($channel->is_open() && $this->runListener) {
                    $channel->wait();
                }
                echo 'Gracefully shutdown - ' . (new \DateTimeImmutable())->format('H:i:s') . PHP_EOL;
                break;
        }
    }
}

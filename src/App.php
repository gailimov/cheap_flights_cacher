<?php

namespace app;

use GuzzleHttp\Client;
use League\Container\Container;

class App
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function initDependencies(): Container
    {
        $container = (new Container())->defaultToShared();

        $container->add(Client::class)->addArgument([
            'timeout' => 10
        ]);

        $container->add(ApiClient::class)->addArgument(Client::class);

        $container->add(Service::class)->addArgument(ApiClient::class);

        $container->add(\PDO::class, function () {
            $params = $this->config['db'];
            $dsn = vsprintf('pgsql:host=%s;dbname=%s', [
                $params['host'],
                $params['name']
            ]);

            try {
                $pdo = new \PDO($dsn, $params['user'], $params['password'], [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING
                ]);
            } catch (\PDOException $e) {
                die($e->getMessage());
            }

            return $pdo;
        });

        $container->add(Repository::class)->addArgument(\PDO::class);

        $container->add(Worker::class)->addArguments([
            Service::class,
            Repository::class,
            $this->config['routes']
        ]);

        return $container;
    }
}

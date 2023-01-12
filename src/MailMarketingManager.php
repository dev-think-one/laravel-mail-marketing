<?php


namespace MailMarketing;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use MailMarketing\Drivers\MailChimp;
use MailMarketing\Drivers\MailMarketingInterface;

class MailMarketingManager
{
    /**
     * The application instance.
     */
    protected Application $app;

    /**
     * The array of resolved services.
     */
    protected array $services = [];

    /**
     * Create a new Mail marketing manager instance.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->service()->$method(...$parameters);
    }

    public function getDefaultDriver()
    {
        return $this->app['config']['mail-marketing.default'];
    }

    protected function getConfig(string $name)
    {
        return $this->app['config']["mail-marketing.{$name}"] ?? null;
    }

    /**
     * Alternative of 'driver' function
     * @param null $service
     *
     * @return MailMarketingInterface
     */
    public function service($service = null): MailMarketingInterface
    {
        return $this->driver($service);
    }

    /**
     * Get a driver instance.
     *
     * @param string|null $driver
     * @return MailMarketingInterface
     */
    public function driver(?string $driver = null): MailMarketingInterface
    {
        return $this->get($driver ?? $this->getDefaultDriver());
    }

    /**
     * Create a driver instance.
     *
     * @param string $name
     * @return MailMarketingInterface
     */
    protected function get(string $name): MailMarketingInterface
    {
        return $this->services[$name] ?? ($this->services[$name] = $this->resolve($name));
    }

    /**
     * @param string $name
     * @return MailMarketingInterface
     */
    protected function resolve(string $name): MailMarketingInterface
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Mail Marketing service [{$name}] is not defined.");
        }

        $driverMethod = 'create' . ucfirst($name) . 'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }

        throw new InvalidArgumentException("Driver [{$name}] is not supported.");
    }

    protected function createMailchimpDriver(array $config): MailChimp
    {
        return new MailChimp($config);
    }
}

<?php


namespace MailMarketing;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use MailMarketing\Drivers\CampaignMonitor;
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
     * The registered custom driver creators.
     *
     * @var array
     */
    protected array $customCreators = [];

    /**
     * Create a new Mail marketing manager instance.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function extend($driver, \Closure $callback)
    {
        $this->customCreators[$driver] = $callback->bindTo($this, $this);

        return $this;
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

        if (isset($this->customCreators[$name])) {
            return $this->callCustomCreator($name, $config);
        }

        $driverMethod = 'create' . ucfirst($name) . 'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }

        throw new InvalidArgumentException("Driver [{$name}] is not supported. [{$driverMethod}]");
    }

    /**
     * Call a custom driver creator.
     *
     * @param  array  $config
     * @return mixed
     */
    protected function callCustomCreator(string $name, array $config): MailMarketingInterface
    {
        return $this->customCreators[$name]($this->app, $config);
    }

    protected function createMailchimpDriver(array $config): MailChimp
    {
        return new MailChimp($config);
    }

    protected function createCampaignmonitorDriver(array $config): CampaignMonitor
    {
        return new CampaignMonitor($config);
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
}

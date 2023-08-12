<?php


namespace MailMarketing\Entities;

use Illuminate\Support\Arr;
use MailMarketing\MailMarketingException;

class MailchimpResponse implements ResponseInterface
{
    protected array $response = [];

    /**
     * @throws MailMarketingException
     */
    public function __construct($response = [])
    {
        if (is_bool($response)) {
            $response = [
                'status' => $response,
            ];
        }
        if (!is_array($response)) {
            throw new MailMarketingException('Mailchimp response not array: ' . print_r($response, true));
        }

        $this->response = $response;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getResponse();
    }

    public function __toString(): string
    {
        return (string) print_r($this->toArray(), true);
    }

    /**
     * @inheritDoc
     */
    public static function init($data): static
    {
        return new static($data);
    }

    /**
     * @inheritDoc
     */
    public function isSuccess(): bool
    {
        if (Arr::has($this->response, 'status') && is_bool(Arr::get($this->response, 'status'))) {
            return Arr::get($this->response, 'status');
        }

        return !(Arr::has($this->response, 'status') && Arr::has($this->response, 'instance'));
    }

    /**
     * @inheritDoc
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @inheritDoc
     */
    public function getErrorMessage(): string
    {
        if ($this->isSuccess()) {
            return '';
        }

        if (Arr::has($this->response, 'errors.0')) {
            return Arr::get($this->response, 'title', 'Error') . ': ' . Arr::get($this->response, 'errors.0.message');
        }

        return Arr::get($this->response, 'title', 'Error') . ': ' . Arr::get($this->response, 'detail', 'Request error');
    }

    /**
     * @inheritDoc
     */
    public function get($key = null, $default = null)
    {
        if (!is_null($key)) {
            return Arr::get($this->getResponse(), $key, $default);
        }

        return $this->getResponse();
    }
}

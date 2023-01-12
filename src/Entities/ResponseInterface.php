<?php


namespace MailMarketing\Entities;

use MailMarketing\MailMarketingException;

interface ResponseInterface
{
    /**
     * Initialise response
     *
     * @param mixed $data
     *
     * @return static
     * @throws MailMarketingException
     */
    public static function init($data): self;

    /**
     * @return mixed
     */
    public function getResponse();

    /**
     * Check is response was successful
     * @return bool
     */
    public function isSuccess(): bool;

    /**
     * Ger error message (empty is success)
     * @return string
     */
    public function getErrorMessage(): string;

    /**
     * Value or full response
     * @param null $key
     * @param null $default
     *
     * @return mixed|array
     */
    public function get($key = null, $default = null);
}

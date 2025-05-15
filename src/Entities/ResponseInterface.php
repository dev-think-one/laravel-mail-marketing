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
    public static function init(mixed $data): static;

    /**
     * @return mixed
     */
    public function getResponse(): mixed;

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
     * @param string|null $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(?string $key = null, mixed $default = null): mixed;
}

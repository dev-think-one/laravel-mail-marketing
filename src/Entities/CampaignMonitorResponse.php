<?php

namespace MailMarketing\Entities;

use Illuminate\Support\Arr;

class CampaignMonitorResponse implements ResponseInterface
{

    protected \CS_REST_Wrapper_Result $response;

    public function __construct(\CS_REST_Wrapper_Result $response)
    {
        $this->response = $response;
    }

    public static function init($data): static
    {
        return new static($data);
    }

    public function getResponse(): \CS_REST_Wrapper_Result
    {
        return $this->response;
    }

    public function isSuccess(): bool
    {
        return $this->response->was_successful();
    }

    public function getErrorMessage(): string
    {
        $response = $this->get('Message');

        return is_string($response) ? $response : '';
    }

    public function get(?string $key = null, mixed $default = null): mixed
    {
        $formattedResponse = json_decode(json_encode($this->response->response), true);
        if (!is_null($key)) {
            return Arr::get($formattedResponse, $key, $default);
        }

        return $formattedResponse;
    }
}

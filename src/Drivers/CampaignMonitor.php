<?php

namespace MailMarketing\Drivers;

use Illuminate\Support\Arr;
use MailMarketing\Entities\CampaignMonitorResponse;
use MailMarketing\Entities\ResponseInterface;
use MailMarketing\MailMarketingException;

/**
 * @see https://www.campaignmonitor.com/api/
 */
class CampaignMonitor implements MailMarketingInterface
{

    protected array $auth;
    protected array $config;

    public function __construct(array $config)
    {
        if (!($config['key'] ?? false)) {
            throw new \InvalidArgumentException('CampaignMonitor API key not present');
        }

        $this->auth = ['api_key' => $config['key']];
        unset($config['key']);

        $this->config = $config;
    }


    /**
     * @param string|null $type
     * @return \CS_REST_Wrapper_Base|\CS_REST_Lists|\CS_REST_Subscribers
     */
    public function makeClient(?string $type = null): \CS_REST_Wrapper_Base
    {
        return match ($type) {
            'lists'       => new \CS_REST_Lists('', $this->auth),
            'subscribers' => new \CS_REST_Subscribers('', $this->auth),
            default       => throw new \InvalidArgumentException("Client type [{$type}] is invalid.")
        };
    }

    /**
     * @inheritDoc
     */
    public function client(?string $type = null): mixed
    {
        return $this->makeClient($type);
    }

    /**
     * @inheritDoc
     */
    public function addList(string $name, array $data = []): ResponseInterface
    {
        $client = Arr::get($data, 'client_id') ?: Arr::get($this->config, 'client_id');
        if (!$client) {
            throw new \InvalidArgumentException('Client is required.');
        }
        
        $listConfig = Arr::get($this->config, 'list', []);
        if (!is_array($listConfig)) {
            $listConfig = [];
        }
        
        $config = array_merge($listConfig, $data, ['Title' => $name]);


        return CampaignMonitorResponse::init($this->client('lists')->create($client, $config));
    }

    public function addMembersToList($listId, $members, array $data = []): ResponseInterface
    {
        $subscribers = [];
        foreach ($members as $member) {
            $subscribers[] = [
                'EmailAddress' => Arr::get($member, 'email', ''),
                'Name'         => implode(' ', array_filter([
                    Arr::get($member, 'first_name', ''),
                    Arr::get($member, 'last_name', ''),
                ])),
                'MobileNumber' => Arr::get($member, 'phone', ''),
                'CustomFields' => [
                    [
                        'Key'   => 'address',
                        'Value' => Arr::get($member, 'address', ''),
                        'Clear' => !Arr::get($member, 'address', ''),
                    ],
                ],
                'ConsentToTrack'   => 'Yes',
                'ConsentToSendSms' => 'Yes',
            ];
        }

        $client = $this->client('subscribers');
        $client->set_list_id($listId);

        return CampaignMonitorResponse::init($client->import($subscribers, Arr::get($data, 'resubscribe', true)));
    }

    public function addMemberToList($listId, $member, array $data = []): ResponseInterface
    {
        $email = Arr::get($member, 'email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new MailMarketingException("Email '{$email}' not valid");
        }
        $customFields     = [];
        $customFieldsData = Arr::get($member, 'custom_fields', null);
        if (is_array($customFieldsData) && !empty($customFieldsData)) {
            foreach ($customFieldsData as $key => $value) {
                $customFields[] = [
                    'Key'   => $key,
                    'Value' => $value,
                ];
            }
        }
        $subscriber = [
            'EmailAddress' => $email,
            'Name'         => implode(' ', array_filter([
                Arr::get($member, 'first_name', ''),
                Arr::get($member, 'last_name', ''),
            ])),
            'MobileNumber' => Arr::get($member, 'phone', ''),
            'CustomFields' => [
                [
                    'Key'   => 'address',
                    'Value' => Arr::get($member, 'address', ''),
                    'Clear' => !Arr::get($member, 'address', ''),
                ],
                ...$customFields,
            ],
            'Resubscribe'      => Arr::get($data, 'resubscribe', true),
            'ConsentToTrack'   => 'Yes',
            'ConsentToSendSms' => 'Yes',
        ];

        $client = $this->client('subscribers');
        $client->set_list_id($listId);

        return CampaignMonitorResponse::init($client->add($subscriber));
    }

    public function removeMemberFromList($listId, $member, array $data = []): ResponseInterface
    {
        $email = Arr::get($member, 'email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new MailMarketingException("Email '{$email}' not valid");
        }

        $client = $this->client('subscribers');
        $client->set_list_id($listId);

        return CampaignMonitorResponse::init($client->delete($email));
    }

    public function getMemberInfoForList($listId, string $email): ResponseInterface
    {
        $client = $this->client('subscribers');
        $client->set_list_id($listId);

        return CampaignMonitorResponse::init($client->get($email));
    }

    public function manageMemberTags($listId, string $email, array $tags): ResponseInterface
    {
        throw new MailMarketingException('CampaignMonitor not support tags');
    }

    public function getMemberTags($listId, string $email): ResponseInterface
    {
        throw new MailMarketingException('CampaignMonitor not support tags');
    }
}

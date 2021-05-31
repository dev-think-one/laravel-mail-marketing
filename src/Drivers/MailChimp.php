<?php


namespace MailMarketing\Drivers;

use Illuminate\Support\Arr;
use MailMarketing\Entities\MailchimpResponse;
use MailMarketing\Entities\ResponseInterface;
use MailMarketing\MailMarketingException;

/**
 * Class MailChimp
 * @see https://mailchimp.com/developer/api/
 */
class MailChimp implements MailMarketingInterface
{
    protected \DrewM\MailChimp\MailChimp $client;

    protected array $config = [];

    public function __construct(array $config)
    {
        if (! ($config['key'] ?? false)) {
            throw new \InvalidArgumentException('MailChimp API key not present');
        }

        $this->client = new \DrewM\MailChimp\MailChimp($config['key']);
        unset($config['key']);

        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * @inheritDoc
     */
    public function addList(string $name, array $data = []): ResponseInterface
    {
        $config = array_merge(Arr::get($this->config, 'list'), $data);
        $params = [
            'name' => $name,
            'contact' => Arr::get($config, 'contact'),
            'permission_reminder' => Arr::get($config, 'permission_reminder'),
            'campaign_defaults' => Arr::get($config, 'campaign_defaults'),
            'email_type_option' => Arr::get($config, 'email_type_option'),
        ];

        return MailchimpResponse::init($this->client->post('lists', $params));
    }

    /**
     * @inheritDoc
     */
    public function addMembersToList($listId, $members, array $data = []): ResponseInterface
    {
        $batch = $this->client->new_batch();

        foreach ($members as $member) {
            $email = Arr::get($member, 'email');
            $batch->put(
                $listId . '__' . Arr::get($member, 'id'),
                "lists/$listId/members/" . $this->client::subscriberHash($email),
                [
                    'email_address' => $email,
                    'status' => 'subscribed',
                    'skip_merge_validation' => true,
                    'merge_fields' => [
                        'FNAME' => Arr::get($member, 'first_name', ''),
                        'LNAME' => Arr::get($member, 'last_name', ''),
                        'PHONE' => Arr::get($member, 'phone', ''),
                        'ADDRESS' => Arr::get($member, 'address', ''),
                    ],
                ]
            );
        }

        return MailchimpResponse::init($batch->execute());
    }

    /**
     * @inheritDoc
     */
    public function addMemberToList($listId, $member, array $data = []): ResponseInterface
    {
        $email = Arr::get($member, 'email');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new MailMarketingException("Email '{$email}' not valid");
        }
        $response = $this->client->put(
            "lists/$listId/members/" . $this->client::subscriberHash($email),
            [
                'email_address' => $email,
                'status_if_new' => 'subscribed',
                'status' => 'subscribed',
                'skip_merge_validation' => false,
                'merge_fields' => [
                    'FNAME' => Arr::get($member, 'first_name', ''),
                    'LNAME' => Arr::get($member, 'last_name', ''),
                    'PHONE' => Arr::get($member, 'phone', ''),
                    'ADDRESS' => Arr::get($member, 'address', ''),
                ],
            ]
        );

        return MailchimpResponse::init($response);
    }

    /**
     * @inheritDoc
     */
    public function removeMemberFromList($listId, $member, array $data = []): ResponseInterface
    {
        $email = Arr::get($member, 'email');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new MailMarketingException("Email '{$email}' not valid");
        }
        $response = $this->client->patch(
            "lists/$listId/members/" . $this->client::subscriberHash($email),
            [
                'email_address' => $email,
                'status' => 'unsubscribed',
                'skip_merge_validation' => false,
                'merge_fields' => [
                    'FNAME' => Arr::get($member, 'first_name', ''),
                    'LNAME' => Arr::get($member, 'last_name', ''),
                    'PHONE' => Arr::get($member, 'phone', ''),
                    'ADDRESS' => Arr::get($member, 'address', ''),
                ],
            ]
        );

        return MailchimpResponse::init($response);
    }

    /**
     * @inheritDoc
     */
    public function getMemberInfoForList($listId, string $email): ResponseInterface
    {
        $response = $this->client->get("lists/{$listId}/members/{$this->client::subscriberHash($email)}");

        return MailchimpResponse::init($response);
    }

    /**
     * @inheritDoc
     */
    public function manageMemberTags($listId, string $email, array $tags): ResponseInterface
    {
        $tags = $this->formatTagsBeforeSend($tags);

        $response = false;
        if (count($tags) > 0) {
            $response = $this->client->post(
                "lists/{$listId}/members/{$this->client::subscriberHash($email)}/tags",
                [
                    'tags' => $tags,
                ]
            );
        }

        return MailchimpResponse::init($response);
    }

    /**
     * @inheritDoc
     */
    public function getMemberTags($listId, string $email): ResponseInterface
    {
        $response = $this->client->get("lists/{$listId}/members/{$this->client::subscriberHash($email)}/tags");

        return MailchimpResponse::init($response);
    }

    /**
     * @param array $tags
     * $tags can be:
     * ['tag1', 'tag2', 'tag3']
     * ['tag1' => true, 'tag2' => false, 'tag3' => true]
     * [['name' => 'tag1'], ['name' => 'tag2', 'status' => 'inactive'], ['name' => 'tag3', 'status' => 'active']]
     * or combination of these examples
     *
     * @return array
     */
    protected function formatTagsBeforeSend(array $tags): array
    {
        return array_filter(array_map(function ($key, $tag) {
            // ['tag1' => true, 'tag2' => false, 'tag3' => true]
            if (is_string($key) && is_bool($tag)) {
                return [
                    'name' => $key,
                    'status' => $tag ? 'active' : 'inactive',
                ];
            }

            // ['tag1', 'tag2', 'tag3']
            if (is_string($tag)) {
                return [
                    'name' => $tag,
                    'status' => 'active',
                ];
            }

            // [['name' => 'tag1'], ['name' => 'tag2', 'status' => 'inactive'], ['name' => 'tag3', 'status' => 'active']]
            if (is_array($tag) && key_exists('name', $tag)) {
                return $tag;
            }

            return null;
        }, array_keys($tags), $tags));
    }
}

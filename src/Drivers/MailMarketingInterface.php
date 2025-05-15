<?php


namespace MailMarketing\Drivers;

use MailMarketing\Entities\ResponseInterface;
use MailMarketing\MailMarketingException;

interface MailMarketingInterface
{
    /**
     * @return mixed
     */
    public function client(?string $type = null): mixed;

    /**
     * Create New list
     * @param string $name List name
     * @param array $data
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function addList(string $name, array $data = []): ResponseInterface;

    /**
     * @param mixed $listId
     * @param \ArrayAccess|array $members
     * @param array $data
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function addMembersToList(mixed $listId, \ArrayAccess|array $members, array $data = []): ResponseInterface;

    /**
     * @param mixed $listId
     * @param \ArrayAccess|array $member
     * @param array $data
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function addMemberToList(mixed $listId, \ArrayAccess|array $member, array $data = []): ResponseInterface;

    /**
     * @param mixed $listId
     * @param \ArrayAccess|array $member
     * @param array $data
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function removeMemberFromList(mixed $listId, \ArrayAccess|array $member, array $data = []): ResponseInterface;

    /**
     * @param mixed $listId
     * @param string $email
     *
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function getMemberInfoForList(mixed $listId, string $email): ResponseInterface;

    /**
     * @param mixed $listId
     * @param string $email
     * @param array $tags
     *
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function manageMemberTags(mixed $listId, string $email, array $tags): ResponseInterface;

    /**
     * @param mixed $listId
     * @param string $email
     *
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function getMemberTags(mixed $listId, string $email): ResponseInterface;
}

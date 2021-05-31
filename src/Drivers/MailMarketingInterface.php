<?php


namespace MailMarketing\Drivers;

use MailMarketing\Entities\ResponseInterface;
use MailMarketing\MailMarketingException;

interface MailMarketingInterface
{
    /**
     * @return mixed
     */
    public function client();

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
    public function addMembersToList($listId, $members, array $data = []): ResponseInterface;

    /**
     * @param mixed $listId
     * @param \ArrayAccess|array $member
     * @param array $data
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function addMemberToList($listId, $member, array $data = []): ResponseInterface;

    /**
     * @param mixed $listId
     * @param \ArrayAccess|array $member
     * @param array $data
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function removeMemberFromList($listId, $member, array $data = []): ResponseInterface;

    /**
     * @param mixed $listId
     * @param string $email
     *
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function getMemberInfoForList($listId, string $email): ResponseInterface;

    /**
     * @param mixed $listId
     * @param string $email
     * @param array $tags
     *
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function manageMemberTags($listId, string $email, array $tags): ResponseInterface;

    /**
     * @param mixed $listId
     * @param string $email
     *
     * @return ResponseInterface
     * @throws MailMarketingException
     */
    public function getMemberTags($listId, string $email): ResponseInterface;
}

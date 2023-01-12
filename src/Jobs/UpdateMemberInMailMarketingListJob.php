<?php

namespace MailMarketing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use MailMarketing\Drivers\MailMarketingInterface;
use MailMarketing\Entities\ResponseInterface;
use MailMarketing\Facades\MailMarketing;

class UpdateMemberInMailMarketingListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ?string $listId = null;

    public \ArrayAccess|array $member = [];

    public bool $isAdd = true;

    public ?string $driver = null;

    public array $tags;

    /**
     * AddMembersToMailMarketingList constructor.
     */
    public function __construct(?string $listId, \ArrayAccess|array $member, bool $isAdd = true, array $tags = [])
    {
        $this->listId = $listId;
        $this->member = $member;
        $this->isAdd  = $isAdd;
        $this->tags   = $tags;
    }


    public function handle(): ?ResponseInterface
    {
        if (!app()->environment(config('mail-marketing.marketing_jobs.envs'))) {
            return null;
        }
        if ($this->isAdd) {
            return $this->addMemberToList();
        }

        return $this->removeMemberFromList();
    }

    public function setDriver(?string $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    protected function addMemberToList(): ResponseInterface
    {
        $driver = $this->driver();

        $response = $driver->addMemberToList(
            $this->listId,
            $this->member
        );

        if (count($this->tags)) {
            $driver->manageMemberTags(
                $this->listId,
                Arr::get($this->member, 'email'),
                $this->tags
            );
        }

        return $response;
    }

    protected function removeMemberFromList(): ResponseInterface
    {
        return $this->driver()->removeMemberFromList(
            $this->listId,
            $this->member
        );
    }

    protected function driver(): MailMarketingInterface
    {
        return MailMarketing::driver($this->driver);
    }
}

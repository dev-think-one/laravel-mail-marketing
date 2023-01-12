<?php

namespace MailMarketing\Models;

use Illuminate\Foundation\Bus\PendingDispatch;
use MailMarketing\Jobs\UpdateMemberInMailMarketingListJob;

trait InMailMarketing
{
    protected function mailMarketingMemberData(): array
    {
        return $this->only([
            'email',
            'first_name',
            'last_name',
            'phone',
        ]);
    }

    public function updateMemberInMailMarketingList($listId, bool $isAdd = true, array $tags = []): PendingDispatch
    {
        return UpdateMemberInMailMarketingListJob::dispatch(
            $listId,
            $this->mailMarketingMemberData(),
            $isAdd,
            $tags
        );
    }
}

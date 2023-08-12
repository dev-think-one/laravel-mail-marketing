<?php

namespace MailMarketing\Tests;

use Illuminate\Support\Facades\Queue;
use MailMarketing\Jobs\UpdateMemberInMailMarketingListJob;
use MailMarketing\Tests\Fixtures\Models\User;

class InMailMarketingTest extends TestCase
{
    /** @test */
    public function mail_marketing_member_data()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $mailMarketingMemberData = $user->mailMarketingMemberData();

        $this->assertCount(4, $mailMarketingMemberData);

        $this->assertEquals($user->email, $mailMarketingMemberData['email']);
        $this->assertEquals($user->first_name, $mailMarketingMemberData['first_name']);
        $this->assertEquals($user->last_name, $mailMarketingMemberData['last_name']);
        $this->assertEquals($user->phone, $mailMarketingMemberData['phone']);
    }

    /** @test */
    public function register_show_contact()
    {
        /** @var User $user */
        $user = User::factory()->create();

        Queue::fake();

        Queue::assertNothingPushed();

        $user->updateMemberInMailMarketingList('foo_bar', true, ['baz', 'qux']);

        Queue::assertPushed(
            UpdateMemberInMailMarketingListJob::class,
            function (UpdateMemberInMailMarketingListJob $job) use ($user) {
                $this->assertEquals($user->email, $job->member['email']);
                $this->assertEquals($user->first_name, $job->member['first_name']);
                $this->assertEquals($user->last_name, $job->member['last_name']);
                $this->assertEquals($user->phone, $job->member['phone']);

                $this->assertTrue($job->isAdd);

                $this->assertCount(2, $job->tags);
                $this->assertEquals('baz', $job->tags[0]);
                $this->assertEquals('qux', $job->tags[1]);

                return true;
            }
        );
    }
}

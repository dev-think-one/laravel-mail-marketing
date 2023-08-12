<?php

namespace MailMarketing\Tests;

use MailMarketing\Drivers\MailChimp;
use MailMarketing\Drivers\MailMarketingInterface;
use MailMarketing\Facades\MailMarketing;
use MailMarketing\Jobs\UpdateMemberInMailMarketingListJob;
use MailMarketing\Tests\Fixtures\Models\User;
use Mockery\MockInterface;

class UpdateMemberInMailMarketingListJobTest extends TestCase
{
    /** @test */
    public function has_default_driver()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $job = new UpdateMemberInMailMarketingListJob('test', $user->mailMarketingMemberData());

        $this->assertInstanceOf(MailChimp::class, $job->driver());
    }

    /** @test */
    public function not_run_in_wrong_env()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $job = new UpdateMemberInMailMarketingListJob('test', $user->mailMarketingMemberData());

        $this->assertNull($job->handle());
    }

    /** @test */
    public function add_to_list()
    {
        app('config')->set('mail-marketing.marketing_jobs.envs', ['testing']);

        /** @var User $user */
        $user = User::factory()->create();

        $mock = $this->mock(MailMarketingInterface::class, function (MockInterface $mock) use ($user) {
            $mock->shouldReceive('addMemberToList')
                ->withArgs(['foo_test', $user->mailMarketingMemberData()])->once();
            $mock->shouldReceive('manageMemberTags')
                ->withArgs(['foo_test', $user->email, ['quex', 'qux']])->once();
        });

        MailMarketing::extend('baz', fn () => $mock);


        $job = (new UpdateMemberInMailMarketingListJob('foo_test', $user->mailMarketingMemberData(), true, ['quex', 'qux']))
            ->setDriver('baz');

        $this->assertInstanceOf($mock::class, $job->driver());

        $job->handle();
    }

    /** @test */
    public function remove_from_list()
    {
        app('config')->set('mail-marketing.marketing_jobs.envs', ['testing']);

        /** @var User $user */
        $user = User::factory()->create();

        $mock = $this->mock(MailMarketingInterface::class, function (MockInterface $mock) use ($user) {
            $mock->shouldReceive('removeMemberFromList')
                ->withArgs(['foo_test', $user->mailMarketingMemberData()])->once();
        });

        MailMarketing::extend('baz', fn () => $mock);


        $job = (new UpdateMemberInMailMarketingListJob('foo_test', $user->mailMarketingMemberData(), false, ['quex', 'qux']))
            ->setDriver('baz');

        $this->assertInstanceOf($mock::class, $job->driver());

        $job->handle();
    }
}

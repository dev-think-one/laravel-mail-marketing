<?php


namespace MailMarketing\Tests;

use MailMarketing\Drivers\MailChimp;
use MailMarketing\MailMarketingException;
use Mockery\MockInterface;

class MailchimpClientDriverTest extends TestCase
{

    /** @test */
    public function add_list()
    {
        $mock = $this->mock(\DrewM\MailChimp\MailChimp::class, function (MockInterface $mock) {
            $mock->shouldReceive('post')->once()
                ->andReturn(true);
        });

        $driver = (new MailChimp(['key' => config('mail-marketing.mailchimp.key'), 'list' => []]))->setClient($mock);

        $driver->addList('example', []);
    }

    /** @test */
    public function add_member_to_list()
    {
        $mock = $this->mock(\DrewM\MailChimp\MailChimp::class, function (MockInterface $mock) {
            $mock->shouldReceive('subscriberHash')->once()
                ->andReturn('xxx');
            $mock->shouldReceive('put')->once()
                ->andReturn(true);
        });

        $driver = (new MailChimp(['key' => config('mail-marketing.mailchimp.key'), 'list' => []]))->setClient($mock);

        $driver->addMemberToList('example', ['email' => 'foo@baz.qux']);
    }

    /** @test */
    public function add_member_to_list_error_if_incorrect_email()
    {
        $driver = (new MailChimp(['key' => config('mail-marketing.mailchimp.key'), 'list' => []]));

        $this->expectException(MailMarketingException::class);

        $driver->addMemberToList('example', ['email' => 'blablabla']);
    }

    /** @test */
    public function remove_member_from_list()
    {
        $mock = $this->mock(\DrewM\MailChimp\MailChimp::class, function (MockInterface $mock) {
            $mock->shouldReceive('subscriberHash')->once()
                ->andReturn('xxx');
            $mock->shouldReceive('patch')->once()
                ->andReturn(true);
        });

        $driver = (new MailChimp(['key' => config('mail-marketing.mailchimp.key'), 'list' => []]))->setClient($mock);

        $driver->removeMemberFromList('example', ['email' => 'foo@baz.qux']);
    }

    /** @test */
    public function remove_member_from_list_error_if_incorrect_email()
    {
        $driver = (new MailChimp(['key' => config('mail-marketing.mailchimp.key'), 'list' => []]));

        $this->expectException(MailMarketingException::class);

        $driver->removeMemberFromList('example', ['email' => 'blablabla']);
    }

    /** @test */
    public function add_members_to_list()
    {
        $mockBatch = $this->mock(\DrewM\MailChimp\Batch::class, function (MockInterface $mock) {
            $mock->shouldReceive('put')->once();
            $mock->shouldReceive('execute')->once()
                ->andReturn(true);
        });

        $mock = $this->mock(\DrewM\MailChimp\MailChimp::class, function (MockInterface $mock) use ($mockBatch) {
            $mock->shouldReceive('new_batch')->once()
                ->andReturn($mockBatch);
            $mock->shouldReceive('subscriberHash')->once()
                ->andReturn('xxx');
        });

        $driver = (new MailChimp(['key' => config('mail-marketing.mailchimp.key'), 'list' => []]))->setClient($mock);

        $driver->addMembersToList('example', [['email' => 'foo@baz.qux']], []);
    }

    /** @test */
    public function get_member_info_for_list()
    {
        $mock = $this->mock(\DrewM\MailChimp\MailChimp::class, function (MockInterface $mock) {
            $mock->shouldReceive('subscriberHash')->once()
                ->andReturn('xxx');
            $mock->shouldReceive('get')->once()
                ->andReturn(true);
        });

        $driver = (new MailChimp(['key' => config('mail-marketing.mailchimp.key'), 'list' => []]))->setClient($mock);

        $driver->getMemberInfoForList('example', 'foo@baz.qux');
    }

    /** @test */
    public function get_member_tags()
    {
        $mock = $this->mock(\DrewM\MailChimp\MailChimp::class, function (MockInterface $mock) {
            $mock->shouldReceive('subscriberHash')->once()
                ->andReturn('xxx');
            $mock->shouldReceive('get')->once()
                ->andReturn(true);
        });

        $driver = (new MailChimp(['key' => config('mail-marketing.mailchimp.key'), 'list' => []]))->setClient($mock);

        $driver->getMemberTags('example', 'foo@baz.qux');
    }

    /** @test */
    public function manage_member_tags()
    {
        $mock = $this->mock(\DrewM\MailChimp\MailChimp::class, function (MockInterface $mock) {
            $mock->shouldReceive('subscriberHash')->once()
                ->andReturn('xxx');
            $mock->shouldReceive('post')->once()
                ->andReturn(true);
        });

        $driver = (new MailChimp(['key' => config('mail-marketing.mailchimp.key'), 'list' => []]))->setClient($mock);

        $driver->manageMemberTags('example', 'foo@baz.qux', ['foo']);
    }

}

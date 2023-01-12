<?php


namespace MailMarketing\Tests;

use MailMarketing\Drivers\MailChimp;
use MailMarketing\Facades\MailMarketing;

class MailMarketingManagerTest extends TestCase
{
    /** @test */
    public function has_default_driver()
    {
        $this->assertEquals(config('mail-marketing.default'), MailMarketing::getDefaultDriver());
    }

    /** @test */
    public function can_select_driver()
    {
        $this->assertInstanceOf(MailChimp::class, MailMarketing::driver());

        $this->assertInstanceOf(MailChimp::class, MailMarketing::driver('mailchimp'));


        $this->expectException(\InvalidArgumentException::class);
        $this->assertInstanceOf(MailChimp::class, MailMarketing::driver('not_exists'));
    }

    /** @test */
    public function can_select_service()
    {
        $this->assertInstanceOf(MailChimp::class, MailMarketing::service());

        $this->assertInstanceOf(MailChimp::class, MailMarketing::service('mailchimp'));


        $this->expectException(\InvalidArgumentException::class);
        $this->assertInstanceOf(MailChimp::class, MailMarketing::service('not_exists'));
    }

    /** @test */
    public function propagate_request_to_service()
    {
        $this->assertInstanceOf(\DrewM\MailChimp\MailChimp::class, MailMarketing::client());
    }

    /** @test */
    public function driver_should_be()
    {
        app()['config']->set('mail-marketing.otherDriver', []);

        $this->expectException(\InvalidArgumentException::class);

        MailMarketing::driver('otherDriver');
    }
}

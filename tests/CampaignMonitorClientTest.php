<?php


namespace MailMarketing\Tests;

use MailMarketing\Drivers\CampaignMonitor;

class CampaignMonitorClientTest extends TestCase
{
    /** @test */
    public function key_is_required()
    {
        $this->expectException(\InvalidArgumentException::class);
        new CampaignMonitor([]);
    }

    /** @test */
    public function real_error_response()
    {
        $response = \MailMarketing\Facades\MailMarketing::driver('campaignmonitor')->addList('Foo bar');

        $this->assertFalse($response->isSuccess());
        $this->assertEquals('Must supply a valid HTTP Basic Authorization header', $response->getErrorMessage());
        $this->assertEquals(50, $response->get('Code'));
    }
}

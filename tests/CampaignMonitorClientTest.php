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
        $this->assertEquals('Invalid API Key', $response->getErrorMessage());
        $this->assertEquals(100, $response->get('Code'));
    }
}

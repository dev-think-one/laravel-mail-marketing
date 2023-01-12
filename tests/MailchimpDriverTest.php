<?php


namespace MailMarketing\Tests;

use MailMarketing\Drivers\MailChimp;

class MailchimpDriverTest extends TestCase
{
    /** @test */
    public function key_is_required()
    {
        $this->expectException(\InvalidArgumentException::class);
        new MailChimp([]);
    }


    /** @test */
    public function tags_as_string()
    {
        $driver = new MailChimp([ 'key' => config('mail-marketing.mailchimp.key') ]);

        $formatted = $this->invokeMethod($driver, 'formatTagsBeforeSend', [
            'tags' => [ 'test_1', 0, 'test_2' ],
        ]);

        $this->assertCount(2, $formatted);

        foreach ($formatted as $tag) {
            $this->assertArrayHasKey('name', $tag);
            $this->assertArrayHasKey('status', $tag);
            $this->assertEquals('active', $tag['status']);
        }
    }

    /** @test */
    public function tags_as_array()
    {
        $driver = new MailChimp([ 'key' => config('mail-marketing.mailchimp.key') ]);

        $formatted = $this->invokeMethod($driver, 'formatTagsBeforeSend', [
            'tags' => [ [ 'name' => 'test_1' ], [ 'name' => 'test_2' ] ],
        ]);

        $this->assertCount(2, $formatted);

        foreach ($formatted as $tag) {
            $this->assertArrayHasKey('name', $tag);
            $this->assertArrayNotHasKey('status', $tag);
        }

        $formatted = $this->invokeMethod($driver, 'formatTagsBeforeSend', [
            'tags' => [
                [ 'name' => 'test_1', 'status' => 'inactive' ],
                [ 'name' => 'test_2', 'status' => 'inactive' ],
            ],
        ]);

        $this->assertCount(2, $formatted);

        foreach ($formatted as $tag) {
            $this->assertArrayHasKey('name', $tag);
            $this->assertArrayHasKey('status', $tag);
            $this->assertEquals('inactive', $tag['status']);
        }
    }

    /** @test */
    public function tags_as_mixed()
    {
        $driver = new MailChimp([ 'key' => config('mail-marketing.mailchimp.key') ]);

        $formatted = $this->invokeMethod($driver, 'formatTagsBeforeSend', [
            'tags' => [ 'test_1', [ 'name' => 'test_2', 'status' => 'active' ] ],
        ]);

        $this->assertCount(2, $formatted);

        foreach ($formatted as $tag) {
            $this->assertArrayHasKey('name', $tag);
            $this->assertArrayHasKey('status', $tag);
            $this->assertEquals('active', $tag['status']);
        }
    }

    /**
     * @test
     * ['tag1' => true, 'tag2' => false, 'tag3' => true]
     */
    public function tags_as_bool()
    {
        $driver = new MailChimp([ 'key' => config('mail-marketing.mailchimp.key') ]);

        $formatted = $this->invokeMethod($driver, 'formatTagsBeforeSend', [
            'tags' => ['tag1' => true, 'tag2' => false, 'tag3' => true],
        ]);

        $this->assertCount(3, $formatted);

        foreach ($formatted as $tag) {
            $this->assertArrayHasKey('name', $tag);
            $this->assertArrayHasKey('status', $tag);

            if ($tag['name'] === 'tag2') {
                $this->assertEquals('inactive', $tag['status']);
            } else {
                $this->assertEquals('active', $tag['status']);
            }
        }
    }
}

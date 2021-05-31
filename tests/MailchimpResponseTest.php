<?php


namespace MailMarketing\Tests;

use MailMarketing\Entities\MailchimpResponse;
use MailMarketing\MailMarketingException;

class MailchimpResponseTest extends TestCase
{

    /** @test */
    public function response_array()
    {
        $response = new MailchimpResponse([ 'key' => 'value', 'multi' => [ 'subkey' => 'data' ] ]);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('value', $response->get('key'));
        $this->assertEquals('data', $response->get('multi.subkey'));

        $response = MailchimpResponse::init([
            'status' => false,
            'title' => 'RequestError',
            'errors' => [
                [ 'message' => 'Some error' ],
            ],
        ]);

        $this->assertFalse($response->isSuccess());
        $this->assertEquals(false, $response->get('status'));
        $this->assertIsArray($response->get());
        $this->assertEquals('RequestError', $response->toArray()['title']);
        $this->assertEquals('Some error', $response->getResponse()['errors'][0]['message']);
        $this->assertEquals('RequestError: Some error', $response->getErrorMessage());
        $this->assertEquals(print_r($response->getResponse(), true), $response->__toString());
    }

    /** @test */
    public function response_bool()
    {
        $response = new MailchimpResponse(true);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals(true, $response->get('status'));
        $this->assertIsArray($response->get());
        $this->assertEquals('', $response->getErrorMessage());

        $response = new MailchimpResponse(false);

        $this->assertFalse($response->isSuccess());
        $this->assertEquals(false, $response->get('status'));
        $this->assertIsArray($response->get());
        $this->assertEquals('Error: Request error', $response->getErrorMessage());

        $response = MailchimpResponse::init([
            'status' => false,
            'title' => 'RequestError',
            'errors' => [
                [ 'message' => 'Some error' ],
            ],
        ]);

        $this->assertFalse($response->isSuccess());
        $this->assertEquals(false, $response->get('status'));
        $this->assertIsArray($response->get());
        $this->assertEquals('RequestError', $response->toArray()['title']);
        $this->assertEquals('Some error', $response->getResponse()['errors'][0]['message']);
        $this->assertEquals('RequestError: Some error', $response->getErrorMessage());
        $this->assertEquals(print_r($response->getResponse(), true), $response->__toString());
    }

    /** @test */
    public function not_array_return_exception()
    {
        $this->expectException(MailMarketingException::class);
        MailchimpResponse::init('test');
    }
}

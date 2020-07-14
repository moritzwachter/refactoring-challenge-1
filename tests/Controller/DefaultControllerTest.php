<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    const HOST = 'http://refactoring.localtest.me';

    public function testIfReadmeRequestsAreWorking()
    {
        $client = static::createClient();
        $client->request('GET', self::HOST . '/?file=video_12345_q8c.mp4&directory=video1&quality=q6a&type=video');

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $client->request('GET', self::HOST . '/?file=image_12345.png&directory=image2&type=image');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testNonExistingTypeParameter()
    {
        $client = static::createClient();
        $client->request('GET', self::HOST . '/?file=video_12345_q8c.mp4&directory=video1&quality=q6a&type=audio');

        $response = $client->getResponse();
        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame(['status' => 'error'], $response->getContent());
    }
}

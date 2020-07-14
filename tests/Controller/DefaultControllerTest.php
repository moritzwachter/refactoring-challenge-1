<?php

namespace App\Tests\Controller;

use App\Controller\DefaultController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIfReadmeRequestsAreWorking()
    {
        $host = 'http://refactoring.localtest.me';
        $client = static::createClient();
        $client->request('GET', $host . '/?file=video_12345_q8c.mp4&directory=video1&quality=q6a&type=video');

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $client->request('GET', $host . '/?file=image_12345.png&directory=image2&type=image');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
    }
}

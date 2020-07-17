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
        $this->assertJsonStringEqualsJsonString('{"status": "error"}', $response->getContent());
        $this->assertSame(500, $response->getStatusCode());
    }

    /**
     * @dataProvider parsingImageIdDataProvider
     *
     * @param $filename
     * @param $expectToFindEntity
     */
    public function testParsingImageId($filename, $expectToFindEntity)
    {
        $url = self::HOST . "/?file=${filename}&directory=irrelevant&quality=irrelevant&type=image";
        $client = static::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();

        if ($expectToFindEntity) {
            $this->assertSame(200, $response->getStatusCode());
        } else {
            $this->assertSame(500, $response->getStatusCode());
        }
    }

    public function parsingImageIdDataProvider(): array
    {
        return [
            ['image_12345.irrelevant', true],
            ['image_12346.gif', true],
            ['image-something_12347.mp4', true],
            ['image_12348.jpg', false],
            ['image_12349.jpg', false],
            ['image12349.jpg', false],
            ['im_age_12345.jpg', false],
        ];
    }

    /**
     * @dataProvider parsingVideoIdDataProvider
     *
     * @param $filename
     * @param $expectToFindEntity
     */
    public function testParsingVideoId($filename, $quality, $expectToFindEntity)
    {
        $url = self::HOST . "/?file=${filename}&directory=irrelevant&quality=${quality}&type=video";
        $client = static::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();

        if ($expectToFindEntity) {
            $this->assertSame(200, $response->getStatusCode());
        } else {
            $this->assertSame(500, $response->getStatusCode());
        }
    }

    public function parsingVideoIdDataProvider(): array
    {
        return [
            ['video_12345_Q6A.irrelevant', 'q8c', true],
            ['vid-eo_12346_ASDF.irrelevant', 'q6a', true],
            ['_12347_ThisIsCompletely.irrelevant', 'q4a', true],
            ['_12347_ThisIsCompletely.irrelevant', 'Q4A', false],
            ['video_12347.mp4', 'q8c', false],
            ['video_12348_Q8C.mp4', 'q8c', false],
            ['video12349.jpg', 'q8c', false],
            ['vi_deo_12345.jpg', 'q8c', false],
        ];
    }
}

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
        $this->assertJsonStringEqualsJsonString('{"status": "Invalid type"}', $response->getContent());
        $this->assertSame(500, $response->getStatusCode());
    }

    /**
     * @dataProvider parsingImageIdDataProvider
     *
     * @param $filename
     * @param $expectToFindEntity
     */
    public function testParsingImageId(string $filename, int $expectedStatusCode): void
    {
        $url = self::HOST . "/?file=${filename}&directory=irrelevant&quality=irrelevant&type=image";
        $client = static::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();

        $this->assertSame($expectedStatusCode, $response->getStatusCode());
    }

    public function parsingImageIdDataProvider(): array
    {
        return [
            ['image_12346.gif', 200],
            ['image_12348.jpg', 404],
            ['image_12349.jpg', 404],
            ['image-something_12347.mp4', 500],
            ['image_12345.irrelevant', 500],
            ['image12349.jpg', 500],
            ['im_age_12345.jpg', 500],
        ];
    }

    /**
     * @dataProvider parsingVideoIdDataProvider
     *
     * @param string $filename
     * @param string $quality
     * @param int $expectedStatusCode
     */
    public function testParsingVideoId(string $filename, string $quality, int $expectedStatusCode): void
    {
        $url = self::HOST . "/?file=${filename}&directory=irrelevant&quality=${quality}&type=video";
        $client = static::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();
        $this->assertSame($expectedStatusCode, $response->getStatusCode());
    }

    public function parsingVideoIdDataProvider(): array
    {
        return [
            ['video_12345_Q8C.mp4', 'Q8C', 200],
            ['video_12348_Q8C.mp4', 'q8c', 404],
            ['video_12345_Q6A.irrelevant', 'q8c', 500],
            ['vid-eo_12346_ASDF.irrelevant', 'q6a', 500],
            ['_12347_ThisIsCompletely.irrelevant', 'q4a', 500],
            ['_12347_ThisIsCompletely.irrelevant', 'Q4A', 500],
            ['video_12347.mp4', 'q8c', 500],
            ['video12349.jpg', 'q8c', 500],
            ['vi_deo_12345.jpg', 'q8c', 500],
        ];
    }
}

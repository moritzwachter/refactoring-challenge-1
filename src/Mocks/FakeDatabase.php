<?php

namespace App\Mocks;

class FakeDatabase
{
    /** @var Quality[] */
    private $qualities;

    /** @var Video[] */
    private $videos;

    /** @var Image[] */
    private $images;

    public function __construct()
    {
        $this->qualities = $this->createAllQualities();
        $this->videos = $this->createAllVideos();
        $this->images = $this->createAllImages();
    }

    /**
     * @return Image[]
     */
    private function createAllImages() : array
    {
        $images = [];

        for ($i = 0; $i < 3; $i++) {
            $id = 12345 + $i;

            $image = new Image();
            $image->setId($id);
            $image->setFilename(sprintf('image_%s.png', $id));
            $image->setDirectory('imageDirectory');
            $image->setIsDistributed(false);

            $images[$id] = $image;
        }

        return $images;
    }

    /**
     * @return Video[]
     */
    private function createAllVideos() : array
    {
        $videos = [];

        for ($i = 0; $i < 3; $i++) {
            $id = 12345 + $i;
            $qualityKeys = array_keys($this->getQualities());
            $quality = $this->getQualities()[$qualityKeys[$i]];

            $video = new Video();
            $video->setId($id);
            $video->setFilename(sprintf('video_%s_%s.mp4', $id, $quality->getKey()));
            $video->setDirectory('videoDirectory');
            $video->setQuality($quality);
            $video->setIsDistributed(false);

            $videos[$id] = $video;
        }

        return $videos;
    }

    /**
     * @return Quality[]
     */
    private function createAllQualities() : array
    {
        return [
            'q8c' => new Quality('q8c', 'sehr hoch'),
            'q6a' => new Quality('q6a', 'hoch'),
            'q4a' => new Quality('q4a', 'mittel')
        ];
    }

    /**
     * @return Quality[]
     */
    public function getQualities(): array
    {
        return $this->qualities;
    }

    /**
     * @param Quality[] $qualities
     * @return FakeDatabase
     */
    public function setQualities(array $qualities): FakeDatabase
    {
        $this->qualities = $qualities;
        return $this;
    }

    /**
     * @return Video[]
     */
    public function getVideos(): array
    {
        return $this->videos;
    }

    /**
     * @param Video[] $videos
     * @return FakeDatabase
     */
    public function setVideos(array $videos): FakeDatabase
    {
        $this->videos = $videos;
        return $this;
    }

    /**
     * @return Image[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param Image[] $images
     * @return FakeDatabase
     */
    public function setImages(array $images): FakeDatabase
    {
        $this->images = $images;
        return $this;
    }
}

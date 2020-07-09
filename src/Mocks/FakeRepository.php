<?php

namespace App\Mocks;

use Doctrine\ORM\EntityNotFoundException;

class FakeRepository
{
    /**
     * @var FakeDatabase
     */
    private $database;

    public function __construct(FakeDatabase $database)
    {
        $this->database = $database;
    }

    public function getImageById(int $id)
    {
        if (!array_key_exists($id, $this->database->getImages())) {
            throw new EntityNotFoundException("Object with id {$id} does not exist.");
        }

        return $this->database->getImages()[$id];
    }

    public function getVideoById(int $id)
    {
        if (!array_key_exists($id, $this->database->getVideos())) {
            throw new EntityNotFoundException("Object with id {$id} does not exist.");
        }

        return $this->database->getVideos()[$id];
    }

    public function getQualityByKey(string $key)
    {
        if (!array_key_exists($key, $this->database->getQualities())) {
            throw new EntityNotFoundException("Object with key '{$key}' does not exist.");
        }

        return $this->database->getQualities()[$key];
    }

    public function save($entity)
    {
        if ($entity instanceof Video) {
            $videos = $this->database->getVideos();
            $videos[$entity->getId()] = $entity;
            $this->database->setVideos($videos);
        } elseif ($entity instanceof Image) {
            $images = $this->database->getImages();
            $images[$entity->getId()] = $entity;
            $this->database->setImages($images);
        }
    }
}

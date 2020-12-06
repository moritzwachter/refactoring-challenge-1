<?php

namespace App\Tests\Distribution\Functional;

use App\Distribution\ImageDistributionStrategy;
use App\DTO\DistributionDTO;
use App\Mocks\FakeDatabase;
use App\Mocks\FakeRepository;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ImageDistributionStrategyTest extends TestCase
{
    /** @var FakeRepository */
    private $repo;

    /** @var ImageDistributionStrategy */
    private $strategy;

    public function setUp(): void
    {
        $this->repo = new FakeRepository(new FakeDatabase());
        $this->strategy = new ImageDistributionStrategy($this->repo, new NullLogger());
    }

    public function testDistributeFile()
    {
        // check if it's not distributed before calling the method
        $imageBefore = $this->repo->getImageById('12345');
        $this->assertFalse($imageBefore->isDistributed());

        $dto = new DistributionDTO('image_12345.jpg', 'directory', 'image', '');
        $this->strategy->handleDistribution($dto);

        // check if it's now distributed
        $image = $this->repo->getImageById('12345');
        $this->assertSame('directory', $image->getDirectory());
        $this->assertTrue($image->isDistributed());
    }

    public function testEntityNotFoundException()
    {
        $dto = new DistributionDTO('image_12348.jpg', 'directory', 'image', '');
        $this->expectException(EntityNotFoundException::class);

        $this->strategy->handleDistribution($dto);
    }
}

<?php

namespace App\Tests\Distribution\Functional;

use App\Distribution\VideoDistributionStrategy;
use App\DTO\DistributionDTO;
use App\Mocks\FakeDatabase;
use App\Mocks\FakeRepository;
use App\Mocks\Quality;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class VideoDistributionStrategyTest extends TestCase
{
    /** @var FakeRepository */
    private $repo;

    /** @var VideoDistributionStrategy */
    private $strategy;

    public function setUp(): void
    {
        $this->repo = new FakeRepository(new FakeDatabase());
        $this->strategy = new VideoDistributionStrategy($this->repo, new NullLogger());
    }

    public function testDistributeFile()
    {
        // check if it's not distributed before calling the method
        $imageBefore = $this->repo->getVideoById('12345');
        $this->assertFalse($imageBefore->isDistributed());

        $dto = new DistributionDTO('video_12345_q8c.mp4', 'directory', 'video', 'q8c');
        $this->strategy->handleDistribution($dto);

        // check if it's now distributed
        $video = $this->repo->getVideoById('12345');
        $this->assertSame('directory', $video->getDirectory());
        $this->assertTrue($video->isDistributed());
        $this->assertEquals(new Quality('q8c', 'sehr hoch'), $video->getQuality());
    }

    public function testEntityNotFoundException()
    {
        $dto = new DistributionDTO('video_12348_q8c.mp4', 'directory', 'video', '');
        $this->expectException(EntityNotFoundException::class);

        $this->strategy->handleDistribution($dto);
    }
}

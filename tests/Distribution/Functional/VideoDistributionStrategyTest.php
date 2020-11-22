<?php

namespace App\Tests\Distribution\Functional;

use App\Distribution\VideoDistributionStrategy;
use App\DTO\DistributionDTO;
use App\Mocks\FakeRepository;
use App\Mocks\Quality;
use App\Mocks\Video;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class VideoDistributionStrategyTest extends TestCase
{
    /** @var FakeRepository|\PHPUnit\Framework\MockObject\MockObject */
    private $repo;

    /** @var VideoDistributionStrategy */
    private $strategy;

    public function setUp(): void
    {
        $this->repo = $this->createMock(FakeRepository::class);
        $this->strategy = new VideoDistributionStrategy($this->repo, new NullLogger());
    }

    public function testDistributeFile()
    {
        $videoMock = $this->createMock(Video::class);
        $videoMock
            ->expects($this->once())
            ->method('setDirectory')
            ->with('directory');

        $quality = new Quality('q8c', 'sehr hoch');
        $this->repo->expects($this->once())
            ->method('getQualityByKey')
            ->with('q8c')
            ->willReturn($quality);
        $videoMock->expects($this->once())
            ->method('setQuality')
            ->with($quality);

        $videoMock->expects($this->once())
            ->method('setIsDistributed')
            ->with(true);

        $this->repo
            ->expects($this->once())
            ->method('getVideoById')
            ->with('12345')
            ->willReturn($videoMock);
        $this->repo->expects($this->once())->method('save');

        $dto = new DistributionDTO('video_12345_q8c.mp4', 'directory', 'video', 'q8c');
        $this->strategy->handleDistribution($dto);
    }

    public function testEntityNotFoundException()
    {
        $dto = new DistributionDTO('video12345.jpg', 'directory', 'video', '');
        $this->repo
            ->expects($this->once())
            ->method('getVideoById')
            ->with('0')->willThrowException(new EntityNotFoundException())
        ;
        $this->expectException(EntityNotFoundException::class);

        $this->strategy->handleDistribution($dto);
    }
}

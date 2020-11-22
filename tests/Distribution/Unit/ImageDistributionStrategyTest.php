<?php

namespace App\Tests\Distribution\Unit;

use App\Distribution\ImageDistributionStrategy;
use App\DTO\DistributionDTO;
use App\Mocks\FakeRepository;
use App\Mocks\Image;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ImageDistributionStrategyTest extends TestCase
{
    /** @var FakeRepository|\PHPUnit\Framework\MockObject\MockObject */
    private $repo;

    /** @var ImageDistributionStrategy */
    private $strategy;

    public function setUp(): void
    {
        $this->repo = $this->createMock(FakeRepository::class);
        $this->strategy = new ImageDistributionStrategy($this->repo, new NullLogger());
    }

    public function testDistributeFile()
    {
        $imageMock = $this->createMock(Image::class);
        $imageMock
            ->expects($this->once())
            ->method('setDirectory')
            ->with('directory');

        $imageMock->expects($this->once())
            ->method('setIsDistributed')
            ->with(true);

        $this->repo
            ->expects($this->once())
            ->method('getImageById')
            ->with('12345')
            ->willReturn($imageMock);
        $this->repo->expects($this->once())->method('save');

        $dto = new DistributionDTO('image_12345.jpg', 'directory', 'image', '');
        $this->strategy->handleDistribution($dto);
    }

    public function testEntityNotFoundException()
    {
        $dto = new DistributionDTO('image12345.jpg', 'directory', 'image', '');
        $this->repo
            ->expects($this->once())
            ->method('getImageById')
            ->with('0')->willThrowException(new EntityNotFoundException())
        ;
        $this->expectException(EntityNotFoundException::class);

        $this->strategy->handleDistribution($dto);
    }
}

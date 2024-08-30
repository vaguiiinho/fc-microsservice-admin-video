<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\ListGenres\ListGenresInputDto;
use PHPUnit\Framework\TestCase;
use Core\UseCase\Genre\ListGenresUseCase;
use Mockery;
use stdClass;

class ListGenresUseCaseUnitTest extends TestCase
{
   
    public function test_list_genres()
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        $mockDtoInput = Mockery::mock(ListGenresInputDto::class, [

            'filter' => 'test',
            'order' => 'DESC',
            'page' => 1,
            'totalPage' => 15,
        ]);

        $useCase = new ListGenresUseCase($mockRepository);

        $response = $useCase->execute($mockDtoInput);

        Mockery::close();

    }
}

<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\{
    Category,
    Genre,
};
use App\Repositories\Eloquent\{
    GenreEloquentRepository,
    CategoryEloquentRepository,
};
use App\Repositories\Transaction\DBTransaction;
use Core\UseCase\DTO\Genre\Create\CreateGenreInputDto;
use Core\UseCase\Genre\CreateGenreUseCase;
use Tests\TestCase;

class CreateGenreUseCaseTest extends TestCase
{
    public function test_create()
    {
        $genreRepository = new GenreEloquentRepository(new Genre());
        
        $categoryRepository = new CategoryEloquentRepository(new Category());

        $useCase = new CreateGenreUseCase(
            $genreRepository,
            new DBTransaction(),
            $categoryRepository,
        );

        $response = $useCase->execute(
            new CreateGenreInputDto(
                name: 'Test',
            )
        );
       
        $this->assertDatabaseHas('genres', [
            'name' => 'Test',
        ]);
    }
}

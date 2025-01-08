<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Genre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\UseCase\DTO\Genre\List\ListGenreInputDto;
use Core\UseCase\Genre\ListGenreUseCase;
use Tests\TestCase;

class ListGenreUseCaseTest extends TestCase
{
    public function test_find_by_id()
    {
        $genreRepository = new GenreEloquentRepository(new Genre);

        $useCase = new ListGenreUseCase(
            $genreRepository,
        );

        $genre = Genre::factory()->create();
        $response = $useCase->execute(new ListGenreInputDto(id: $genre->id));

        $this->assertEquals($genre->id, $response->id);
        $this->assertEquals($genre->name, $response->name);
    }
}

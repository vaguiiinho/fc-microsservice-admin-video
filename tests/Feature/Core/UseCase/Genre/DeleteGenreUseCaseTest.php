<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Genre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\UseCase\DTO\Genre\Delete\DeleteGenreInputDto;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Tests\TestCase;

class DeleteGenreUseCaseTest extends TestCase
{
    public function testDeleteGenre()
    {
        $genreRepository = new GenreEloquentRepository(new Genre());

        $useCase = new DeleteGenreUseCase(
            $genreRepository,
        );

        $genre = Genre::factory()->create();
        $response =  $useCase->execute(
            new DeleteGenreInputDto(
                id: $genre->id
            )
        );

        $this->assertTrue($response->success);
        $this->assertSoftDeleted('genres', [
            'id' => $genre->id
        ]);
    }
}

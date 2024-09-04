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
use Core\Domain\Exception\NotFoundException;
use Core\UseCase\DTO\Genre\Update\UpdateGenreInputDto;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Tests\TestCase;

class UpdateGenreUseCaseTest extends TestCase
{
    public function testUpdateGenre()
    {
        $genre = Genre::factory()->create();

        $repository = new GenreEloquentRepository(new Genre());
        $categoryRepository = new CategoryEloquentRepository(new Category());

        $useCase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $categoryRepository,
        );

        $categories = Category::factory()->count(10)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        $response = $useCase->execute(
            new UpdateGenreInputDto(
                id: $genre->id,
                name: 'Updated Test',
                categoriesId: $categoriesId,
            ),
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'Updated Test',
        ]);
        $this->assertDatabaseCount('category_genre', 10);
    }

    public function testExpectUpdateGenreWithCategoriesIdInvalid()
    {
        $this->expectException(NotFoundException::class);

        $genreRepository = new GenreEloquentRepository(new Genre());

        $categoryRepository = new CategoryEloquentRepository(new Category());

        $useCase = new UpdateGenreUseCase(
            $genreRepository,
            new DBTransaction(),
            $categoryRepository,
        );

        $categories = Category::factory()->count(10)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        array_push($categoriesId, 'fake_id');

        $genre = Genre::factory()->create();

        $useCase->execute(
            new UpdateGenreInputDto(
                id: $genre->id,
                name: 'Test',
                categoriesId: $categoriesId,
            )
        );
    }

    public function testTransactionInsert()
    {
        $genreRepository = new GenreEloquentRepository(new Genre());

        $categoryRepository = new CategoryEloquentRepository(new Category());

        $useCase = new UpdateGenreUseCase(
            $genreRepository,
            new DBTransaction(),
            $categoryRepository,
        );

        $categories = Category::factory()->count(10)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        array_push($categoriesId, 'fake_id');

        $genre = Genre::factory()->create();

        try {
            $useCase->execute(
                new UpdateGenreInputDto(
                    id: $genre->id,
                    name: 'Test',
                    categoriesId: $categoriesId,
                )
            );
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('category_genre', 0);
            $this->assertDatabaseCount('genres', 0);
        }
    }
}

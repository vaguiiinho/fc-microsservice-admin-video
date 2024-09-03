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
use Core\UseCase\DTO\Genre\Create\CreateGenreInputDto;
use Core\UseCase\Genre\CreateGenreUseCase;
use Tests\TestCase;

class CreateGenreUseCaseTest extends TestCase
{
    public function testInsert()
    {
        $genreRepository = new GenreEloquentRepository(new Genre());

        $categoryRepository = new CategoryEloquentRepository(new Category());

        $useCase = new CreateGenreUseCase(
            $genreRepository,
            new DBTransaction(),
            $categoryRepository,
        );

        $categories = Category::factory()->count(10)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        $useCase->execute(
            new CreateGenreInputDto(
                name: 'Test',
                categoriesId: $categoriesId,
            )
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'Test',
        ]);

        $this->assertDatabaseCount('category_genre', 10);
    }

    public function testExpectInsertGenreWithCategoriesIdInvalid()
    {
        $this->expectException(NotFoundException::class);

        $genreRepository = new GenreEloquentRepository(new Genre());

        $categoryRepository = new CategoryEloquentRepository(new Category());

        $useCase = new CreateGenreUseCase(
            $genreRepository,
            new DBTransaction(),
            $categoryRepository,
        );

        $categories = Category::factory()->count(10)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        array_push($categoriesId, 'fake_id');

        $useCase->execute(
            new CreateGenreInputDto(
                name: 'Test',
                categoriesId: $categoriesId,
            )
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'Test',
        ]);

        $this->assertDatabaseCount('category_genre', 10);
    }

    public function testTransactionInsert()
    {
        $genreRepository = new GenreEloquentRepository(new Genre());

        $categoryRepository = new CategoryEloquentRepository(new Category());

        $useCase = new CreateGenreUseCase(
            $genreRepository,
            new DBTransaction(),
            $categoryRepository,
        );

        $categories = Category::factory()->count(10)->create();
        $categoriesId = $categories->pluck('id')->toArray();

        array_push($categoriesId, 'fake_id');

        try {
            $useCase->execute(
                new CreateGenreInputDto(
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

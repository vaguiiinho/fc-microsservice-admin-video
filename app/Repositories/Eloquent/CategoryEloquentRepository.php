<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Presenter\PaginationPresenter;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function insert($entity): EntityCategory
    {
        $response = $this->model->create([
            'id' => $entity->id,
            'name' => $entity->name,
            'description' => $entity->description,
            'is_active' => $entity->isActive,
            'created_at' => $entity->createdAt(),
        ]);

        return $this->toCategory($response);
    }


    public function findById(string $id): EntityCategory
    {
        if (!$category = $this->model->find($id)) {
            throw new NotFoundException('Category not found');
        }

        return $this->toCategory($category);
    }

    public function getIdsListIds(array $categoriesId): array
    {
        return $this->model->whereIn('id', $categoriesId)->get()->toArray();
    }
    
    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $categories = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('id', $order)
            ->get();
        return $categories->toArray();
    }
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query->orderBy('id', $order);
        $paginator = $query->paginate();

        return new PaginationPresenter($paginator);
    }
    public function update(EntityCategory $entityCategory): EntityCategory
    {
        if (!$category = $this->model->find($entityCategory->id)) {
            throw new NotFoundException('Category not found');
        }

        $category->update([
            'name' => $entityCategory->name,
            'description' => $entityCategory->description,
            'is_active' => $entityCategory->isActive,
            'created_at' => $entityCategory->createdAt(),
        ]);

        $category->refresh();

        return $this->toCategory($category);
    }
    public function delete(string $categoryId): bool
    {
        if (!$category = $this->model->find($categoryId)) {
            throw new NotFoundException('Category not found');
        }

        return  $category->delete();
    }

    private function toCategory(object $entity): EntityCategory
    {
        return new EntityCategory(
            id: $entity->id,
            name: $entity->name,
            description: $entity->description,
            isActive: $entity->is_active,
        );
    }
}

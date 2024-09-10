<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use App\Repositories\Presenter\PaginationPresenter;
use Core\Domain\Entity\CastMember as Entity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;

class CastMemberRepository implements CastMemberRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(Entity $castMember): Entity {}

    public function findById(string $id): Entity {}

    public function findAll(string $filter = '', $order = 'DESC'): array {}

    public function paginate(
        string $filter = '',
        $order = 'DESC',
        int $page = 1,
        int $totalPage = 15
    ): PaginationInterface {}

    public function update(Entity $castMember): Entity {}

    public function delete(string $castMemberId): bool {}
}

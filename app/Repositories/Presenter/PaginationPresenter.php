<?php

namespace App\Repositories\Presenter;

use Core\Domain\Repository\PaginationInterface;


class PaginationPresenter implements PaginationInterface
{
    public function items(): array
    {
        return [];
    }

    public function total(): int
    {
        return 0;
    }

    public function firstPage(): int
    {
        return 0;
    }

    public function lastPage(): int
    {
        return 0;
    }

    public function currentPage(): int
    {
        return 0;
    }

    public function perPage(): int
    {
        return 0;
    }

    public function to(): int
    {
        return 0;
    }

    public function from(): int
    {
        return 0;
    }
}

<?php

namespace App\DTOs\V1\User\Book;

use Exception;

class SetUpDTO
{
    protected ?bool $pagination;
    protected ?int $limit = 9;
    protected ?string $search;
    protected ?int $category_id;

    public function __construct(public array $data)
    {
        if (!$this->map($this->data)) {
            throw new Exception();
        }
    }

    final protected function map(array $data): bool
    {
        $this->limit = isset($data['limit']) ?? $this->limit;
        $this->search = isset($data['search']) ?? null;
        $this->pagination = isset($data['pagination']) ?? false;
        $this->category_id = isset($data['category_id']) ?? null;
        return true;
    }

    final public function isPaginated(): ?bool
    {
        return $this->pagination;
    }

    final public function getSearch(): ?string
    {
        return $this->search;
    }

    final public function getCategoryId(): ?string
    {
        return $this->category_id;
    }

    final public function getLimit(): ?int
    {
        return $this->limit;
    }
}

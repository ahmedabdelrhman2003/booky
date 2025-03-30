<?php

namespace App\DTOs\V1\User\Book;

use Exception;

class SetUpDTO
{
    protected ?bool $pagination;
    protected ?int $limit = 9;
    protected ? array $filters;

    public function __construct(public array $data)
    {
        if (!$this->map($this->data)) {
            throw new Exception();
        }
    }

    final protected function map(array $data): bool
    {
        $this->limit = $data['limit'] ?? 9;
        $this->pagination = isset($data['pagination']) ?? false;
        $this->filters = $data['filter'] ?? [];
        return true;
    }

    final public function isPaginated(): ?bool
    {
        return $this->pagination;
    }

    final public function getSearch(): ?string
    {
        return $this->filters['search'] ?? null;
    }

    final public function getFilters(): ?array
    {
        return $this->filters;
    }

    final public function getCategoryId(): ?int
    {
        return $this->filters['category_id'] ?? null;
    }

    final public function getLanguage(): ?string
    {
        return $this->filters['language'] ?? null;
    }

    final public function getAuthorId(): ?string
    {
        return $this->filters['author_id'] ?? null;
    }


    final public function getLimit(): ?int
    {
        return $this->limit;
    }
}

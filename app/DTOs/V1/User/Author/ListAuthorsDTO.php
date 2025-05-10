<?php

namespace App\DTOs\V1\User\Author;

use Exception;

class ListAuthorsDTO
{
    protected ?bool $pagination;
    protected ?int $limit = 9;

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
        return true;
    }

    final public function isPaginated(): ?bool
    {
        return $this->pagination;
    }

    final public function getLimit(): ?int
    {
        return $this->limit;
    }
}

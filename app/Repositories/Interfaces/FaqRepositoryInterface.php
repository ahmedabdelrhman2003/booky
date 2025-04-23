<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface FaqRepositoryInterface
{


    public function all(): Collection;

}

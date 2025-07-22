<?php

namespace App\Services;

use App\Models\ParentUser;

class ParentService
{
    public function create(array $data): ParentUser
    {
        return ParentUser::create($data);
    }

    public function update(ParentUser $parent, array $data): ParentUser
    {
        $parent->update($data);
        return $parent;
    }

    public function attachEleve(ParentUser $parent, int $eleveId): void
    {
        $parent->eleves()->syncWithoutDetaching([$eleveId]);
    }
}

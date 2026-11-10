<?php

namespace App\Policies;

use App\Models\StudyMaterial;
use App\Models\User;

class StudyMaterialPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_OPS); }
    public function view(User $user, StudyMaterial $s): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR); }
    public function update(User $user, StudyMaterial $s): bool { return $this->create($user); }
    public function delete(User $user, StudyMaterial $s): bool { return $user->hasRole(User::ROLE_ADMIN); }
}

<?php

namespace App\Policies;

use App\Models\HeroMedia;
use App\Models\User;

class HeroMediaPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_OPS); }
    public function view(User $user, HeroMedia $h): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR); }
    public function update(User $user, HeroMedia $h): bool { return $this->create($user); }
    public function delete(User $user, HeroMedia $h): bool { return $user->hasRole(User::ROLE_ADMIN); }
}

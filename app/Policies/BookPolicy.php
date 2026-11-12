<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_OPS); }
    public function view(User $user, Book $b): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR); }
    public function update(User $user, Book $b): bool { return $this->create($user); }
    public function delete(User $user, Book $b): bool { return $user->hasRole(User::ROLE_ADMIN); }
}

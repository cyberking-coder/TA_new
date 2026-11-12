<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_OPS); }
    public function view(User $user, Review $r): bool { return $this->viewAny($user); }
    public function moderate(User $user, Review $r): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR); }
    public function update(User $user, Review $r): bool { return $this->moderate($user, $r); }
    public function delete(User $user, Review $r): bool { return $user->hasRole(User::ROLE_ADMIN); }
}

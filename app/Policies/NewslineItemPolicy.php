<?php

namespace App\Policies;

use App\Models\NewslineItem;
use App\Models\User;

class NewslineItemPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_OPS); }
    public function view(User $user, NewslineItem $n): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR); }
    public function update(User $user, NewslineItem $n): bool { return $this->create($user); }
    public function delete(User $user, NewslineItem $n): bool { return $user->hasRole(User::ROLE_ADMIN); }
}

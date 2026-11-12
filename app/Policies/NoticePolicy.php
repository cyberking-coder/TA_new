<?php

namespace App\Policies;

use App\Models\Notice;
use App\Models\User;

class NoticePolicy
{
    public function viewAny(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_OPS); }
    public function view(User $user, Notice $n): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR); }
    public function update(User $user, Notice $n): bool { return $this->create($user); }
    public function delete(User $user, Notice $n): bool { return $user->hasRole(User::ROLE_ADMIN); }
    public function publish(User $user, Notice $n): bool { return $this->create($user); }
    public function withdraw(User $user, Notice $n): bool { return $this->create($user); }
}

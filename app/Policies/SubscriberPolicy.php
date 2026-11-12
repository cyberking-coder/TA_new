<?php

namespace App\Policies;

use App\Models\User;

/**
 * Shared policy for OfficerSubscriber + SoldierSubscriber + ResultRegistration lists.
 * Matches permission matrix in docs/permissions-matrix.md.
 */
class SubscriberPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_OPS); }
    public function view(User $user, $m): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_OPS); }
    public function update(User $user, $m): bool { return $this->create($user); }
    public function delete(User $user, $m): bool { return $user->hasRole(User::ROLE_ADMIN); }
    public function export(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_OPS); }
    public function sendNewsletter(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_OPS); }
}

<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;

class ExamPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_OPS); }
    public function view(User $user, Exam $e): bool { return $this->viewAny($user); }
    public function create(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR); }
    public function update(User $user, Exam $e): bool { return $this->create($user); }
    public function delete(User $user, Exam $e): bool { return $user->hasRole(User::ROLE_ADMIN); }
}

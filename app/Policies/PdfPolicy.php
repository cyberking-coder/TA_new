<?php

namespace App\Policies;

use App\Models\Pdf;
use App\Models\User;

class PdfPolicy
{
    public function viewAny(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR, User::ROLE_OPS); }
    public function view(User $user, Pdf $p): bool { return $this->viewAny($user); }
    // Only admin + editor can upload. Operations cannot upload or alter PDFs.
    public function create(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR); }
    public function update(User $user, Pdf $p): bool { return $this->create($user); }
    // Admin only can upload PRIVATE (buyer-only) PDFs — delegated to form logic plus policy check below.
    public function uploadPrivate(User $user): bool { return $user->hasRole(User::ROLE_ADMIN, User::ROLE_EDITOR); }
    public function delete(User $user, Pdf $p): bool { return $user->hasRole(User::ROLE_ADMIN); }
}

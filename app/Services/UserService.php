<?php

namespace App\Services;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Secretary;


class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getAllSecretaries()
    {
        return Secretary::with(['user.role'])
            ->get()
            ->map(function ($secretary) {
                return [
                    'id'       => $secretary->id,
                    'username' => $secretary->username,
                    'email'    => $secretary->user->email ?? null,
                    'role'     => $secretary->user->role->name ?? null,
                ];
            });
    }

    public function getAllTeachers()
    {
        return Teacher::with(['user.role'])
            ->get()
            ->map(function ($teacher) {
                return [
                    'id'       => $teacher->id,
                    'name'     => $teacher->name,
                    'lastname' => $teacher->lastname,
                    'dni'      => $teacher->dni,
                    'cuil'     => $teacher->cuil,
                    'faculty'  => $teacher->faculty,
                    'is_rector'=> $teacher->is_rector,
                    'is_dean'  => $teacher->is_dean,
                    'email'    => $teacher->user->email ?? null,
                    'role'     => $teacher->user->role->name ?? null,
                ];
            });
    }
}

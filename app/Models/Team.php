<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mpociot\Teamwork\TeamworkTeam;

class Team extends TeamworkTeam
{
    use HasFactory;

    public function getUsersWithRolesAttribute()
    {
        $roles = $this->users()
            ->whereHas('teams', function ($query) {
                $query->where('team_id', $this->id);
            })
            ->pluck('team_user.role', 'users.id');

        $users = $this->users->map(function ($user) use ($roles) {
            $user->role = $roles[$user->id] ?? null;
            return $user;
        });

        return $users;
    }

    public function getUsersWithRolesQuery()
    {
        return $this->users()
            ->whereHas('teams', function ($query) {
                $query->where('team_id', $this->id);
            })->select('users.*', 'team_user.role');
    }
}

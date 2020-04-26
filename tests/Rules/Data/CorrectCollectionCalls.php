<?php

declare(strict_types=1);

namespace Tests\Rules\Data;

use App\Account;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CorrectCollectionCalls
{
    public function staticCount(): int
    {
        return User::count();
    }

    public function pluckQuery(): Collection
    {
        return User::query()->pluck('id');
    }

    public function pluckComputed(): Collection
    {
        return User::all()->pluck('allCapsName');
    }

    public function pluckRelation(): Collection
    {
        return User::with(['accounts'])->get()->pluck('accounts');
    }

    public function firstRelation(): ?Account
    {
        return User::firstOrFail()->accounts()->first();
    }

    public function maxQuery(): int
    {
        return DB::table('users')->max('id');
    }

    public function collectionCalls(): int
    {
        return collect([1, 2, 3])->flip()->reverse()->sum();
    }

    /**
     * Can't analyze the closure as a parameter to contains, so should not throw any error.
     * @return bool
     */
    public function testContainsClosure(): bool
    {
        return User::where('id', '>', 1)->get()->contains(function (User $user): bool {
            return $user->id === 2;
        });
    }

    /**
     * Can't analyze the closure as a parameter to first, so should not throw any error.
     * @return User|null
     */
    public function testFirstClosure(): ?User
    {
        return User::where('id', '>', 1)->get()->first(function (User $user): bool {
            return $user->id === 2;
        });
    }
}
<?php

namespace App\Repository;

use App\Models\Chirp;
use App\Models\User;

class ChirpRepository {

    private static Chirp $chirp;

    public function create(User $user, array $chirpRequest): Chirp {
        $chirp = new Chirp($chirpRequest);
        $chirp->user_id = $user->id;
        $chirp->save();

        return $chirp;
    }
}

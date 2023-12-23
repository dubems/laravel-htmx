<?php

namespace App\Services;

use App\Models\Chirp;
use App\Models\User;
use App\Repository\ChirpRepository;

readonly class ChirpService {

    public function __construct(private ChirpRepository $chirpRepository) {

    }

    public function createChirps(User $user, array $chirpRequest): Chirp {
       return $this->chirpRepository->create($user, $chirpRequest);
    }
}

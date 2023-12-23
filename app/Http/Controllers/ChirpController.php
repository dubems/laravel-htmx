<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Services\ChirpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChirpController extends Controller {

    private static string $HTMX_HEADER = 'HX-Request';

    public function __construct(private readonly ChirpService $chirpService) {

    }

    public function index(): View {
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get()
        ]);
    }

    public function create(): ?string {
        return null;
    }

    public function store(Request $request): RedirectResponse|View {
        $validated = $request->validate([
            'message' => 'required|string|max:255'
        ]);

        $chirp = $this->chirpService->createChirps(Auth::user(), $validated);

        return $request->header(self::$HTMX_HEADER)
            ? view('components.chirps.single', ["chirp" => $chirp])
            : redirect(route('chirps.index'));
    }

    public function show(Request $request, Chirp $chirp) {
        return $request->header(self::$HTMX_HEADER)
            ? view('components.chirps.single', ["chirp" => $chirp])
            : view('chirps.index', ["chirp" => $chirp]);
    }

    public function edit(Request $request, Chirp $chirp): View {
        $this->authorize('update', $chirp);

        return $request->header(self::$HTMX_HEADER)
            ? view('components.chirps.edit', ["chirp" => $chirp])
            : view('chirps.edit', ["chirp" => $chirp]);
    }

    public function update(Request $request, Chirp $chirp): RedirectResponse|View {
        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255'
        ]);

        $chirp->update($validated);

        return $request->header(self::$HTMX_HEADER)
            ? view('components.chirps.single', ["chirp" => $chirp])
            : view('chirps.edit', ["chirp" => $chirp]);
    }

    public function destroy(Request $request, Chirp $chirp): RedirectResponse|string {
        $this->authorize('delete', $chirp);
        $chirp->delete();

        return $request->header(self::$HTMX_HEADER)
            ? ''
            : view('chirps.index', ["chirp" => $chirp]);
    }
}

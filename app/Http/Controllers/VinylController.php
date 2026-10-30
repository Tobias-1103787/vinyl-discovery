<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Genre;
use App\Models\Label;
use App\Models\Vinyl;
use App\Services\DiscogsService;
use Illuminate\Http\Request;
use Calliostro\Discogs\DiscogsClientFactory;

use Illuminate\Support\Facades\DB;
use Redirect;
use Illuminate\Support\Facades\Auth;

class VinylController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $vinyls = Vinyl::with(['labels', 'artists', 'genres'])->latest()->get();

        return view('vinyls.index', ['vinyls' => $vinyls]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vinyls.create', ['vinyl' => null]);
    }

    public function discogSearch(Request $request, DiscogsService $discogsService)
    {
        $discogs = DiscogsClientFactory::create();

        $input = trim($request->validate(['query' => 'required|string'])['query']);

        //link-handling
        if (preg_match('#discogs\.com/(?:[a-z-]+/)?release/(\d+)#i', $input, $m)
            || preg_match('/^\d+$/', $input, $m)) {

            $id = (int) ($m[1] ?? $m[0]);
            $release = $discogs->getRelease($id);

            if (!$discogsService->isVinyl($release)) {
                return back()->withErrors(['query' => 'Dit is geen vinyl.']);
            }

            return view('vinyls.create', ['vinyl' => $release]);
        }

        //title search
        $results = collect($discogs->search($input)['results'] ?? [])
            ->filter(fn ($r) => preg_match('#/(master|release)/#i', $r['uri'] ?? ''))
            ->filter(fn ($r) => $discogsService->isVinylResult($r))
            ->values()
            ->all();

        return view('vinyls.create', ['vinyl' => null, 'results' => $results]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DiscogsService $discogsService)
    {
        $id = (int) $request->validate(['discogs_id' => 'required|integer'])['discogs_id'];

        if (Vinyl::where('discogs_id', $id)->exists()) {
            return back()->withErrors(['query' => 'Dit album staat al in de database.']);
        }
        //aanmaken van nieuwe vinyl
        $discogs = DiscogsClientFactory::create();

        try {
            $release = $discogs->getRelease($id);
        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors(['query' => 'Release kon niet worden opgehaald.']);
        }

        if (!$discogsService->isVinyl($release)) {
            return back()->withErrors(['query' => 'Dit is geen vinyl.']);
        }

        $released = $release['released'] ?? '';
        $releaseDate = preg_match('/^\d{4}-(?!00)\d{2}-(?!00)\d{2}$/', $released) ? $released : null;

        DB::transaction(function() use($release, $releaseDate) {
           $vinyl = Vinyl::create([
               'title' => $release['title'],
               'description' => $release['notes'] ?? null,
               'format' => $release['formats'][0]['name'] ?? 'Vinyl',
               'external_link' => $release['uri'] ?? null,
               'discogs_id' => $release['id'],
               'cover_url' => $release['images'][0]['uri'] ?? $release['thumb'] ?? null,
               'release_date' => $releaseDate,
               'created_by'    => auth()->id(),
           ]);

           foreach ($release['artists'] as $a) {
               $artist = Artist::firstOrCreate(
                   ['discogs_id' => $a['id']],
                   ['name' => $a['name']]
               );
               $artistIds[] = $artist->id;
           }

           $separatedLabels = collect($release['labels'])->unique('id');
           foreach ($separatedLabels as $l) {
               $labels = Label::firstOrCreate(
                   ['discogs_id' => $l['id']],
                   ['name' => $l['name']]
               );
               $labelIds[] = $labels->id;
           }

            foreach ($release['genres'] as $g) {
                $genres = Genre::firstOrCreate(
                    ['name' => $g]
                );
                $genreIds[] = $genres->id;
            }

           $vinyl->artists()->syncWithoutDetaching($artistIds);
           $vinyl->labels()->syncWithoutDetaching($labelIds);
           $vinyl->genres()->syncWithoutDetaching($genreIds);
        });
        return Redirect::route('vinyls.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userId = Auth::id();
        $vinyl = Vinyl::with(['labels', 'artists', 'genres'])->findOrFail($id);

        return view('vinyls.show', ['vinyl' => $vinyl, 'currentUserId' => $userId]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vinyl $vinyl)
    {
        abort_unless($vinyl->created_by == auth()->id(), 403);

        return view('vinyls.edit', ['vinyl' => $vinyl]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vinyl $vinyl)
    {
        abort_unless($vinyl->created_by == auth()->id(), 403);

        DB::transaction(function() use($request, $vinyl) {
            $vinyl->updateOrFail($request->validate([
                'title'        => 'required|string|max:255',
                'description'  => 'nullable|string',
                'release_date' => 'nullable|date',
                'format'       => 'required|string|max:255',
            ]));
        });

        return view('vinyls.show', ['vinyl' => $vinyl]);
    }

    //TO-DO: TOGGLE TOEVOEGEN
    public function favorite(Vinyl $vinyl){
        $vinyl->favorites()->firstOrCreate(['user_id' => auth()->id()]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vinyl $vinyl)
    {
        abort_unless($vinyl->created_by == auth()->id(), 403);

        $vinyl->delete();

        return redirect('/');
    }
}

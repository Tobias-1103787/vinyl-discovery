<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Favorite;
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
    public function index(Request $request)
    {
        $allowedFilters = [
            'intact'     => 'Intact',
            'favorite'   => 'Favorieten',
            'own_vinyls' => 'Mijn albums',
        ];

        $vinyls = Vinyl::with(['labels', 'artists', 'genres'])
            ->withExists(['favorites as is_favorite' => fn ($q) => $q->where('user_id', auth()->id())])
            ->when($request->boolean('intact'), fn ($q) => $q->where('intact', true))
            ->when($request->boolean('favorite'), fn ($q) => $q->whereHas('favorites',
                fn ($f) => $f->where('user_id', auth()->id())))
            ->when($request->boolean('own_vinyls'), fn ($q) => $q->where('created_by', auth()->id()))
            ->when($request->filled('searchValue'), function ($q) use ($request) {
                $term = '%' . $request->input('searchValue') . '%';
                $q->where(function ($q) use ($term) {
                    $q->where('title', 'like', $term)
                        ->orWhereHas('artists', fn ($a) => $a->where('name', 'like', $term));
                });
            })
            ->latest()
            ->get();

        return view('vinyls.index', ['vinyls' => $vinyls, 'filters' => $allowedFilters]);
    }
    public function create()
    {
        if (Favorite::where('user_id', Auth::id())->count() < 3) {
            return back()->withErrors(['error' => 'Je hebt minimaal 3 favorites nodig om een vinyl toe te voegen.']);
        }

        return view('vinyls.create', ['vinyl' => null]);
    }

    public function discogSearch(Request $request, DiscogsService $discogsService)
    {
        if (Favorite::where('user_id', Auth::id())->count() < 3) {
            return back()->withErrors(['query' => 'Je hebt minimaal 3 favorites nodig om een vinyl toe te voegen.']);
        }

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

        if(empty($results)){
            return back()->withErrors(['query' => 'Geen resultaten.']);
        }

        return view('vinyls.create', ['vinyl' => null, 'results' => $results]);
    }

    public function store(Request $request, DiscogsService $discogsService)
    {
        if (Favorite::where('user_id', Auth::id())->count() < 3) {
            return back()->withErrors(['query' => 'Je hebt minimaal 3 favorites nodig om een vinyl toe te voegen.']);
        }

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

    public function show(string $id)
    {
        $user = Auth::user();
        $vinyl = Vinyl::with(['labels', 'artists', 'genres'])->findOrFail($id);
        $isFavorite = $vinyl->favorites()->where('user_id', auth()->id())->exists();

        return view('vinyls.show', ['vinyl' => $vinyl, 'currentUser' => $user, 'isFavorite' => $isFavorite]);
    }

    public function edit(Vinyl $vinyl)
    {
        $user = Auth::user();
        abort_unless($vinyl->created_by == auth()->id() or $user->admin, 403);
        return view('vinyls.edit', ['vinyl' => $vinyl]);
    }

    public function update(Request $request, Vinyl $vinyl)
    {
        $user = Auth::user();
        abort_unless($vinyl->created_by == auth()->id() or $user->admin, 403);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'release_date' => 'nullable|date',
            'format'       => 'required|string|max:255',
        ]);
        $data['description'] = isset($data['description'])
            ? str_replace("\r\n", "\n", $data['description']) : null;

        $vinyl->fill($data);

        if (! $vinyl->isDirty()) {
            return back()->withErrors(['error' => 'Geen wijzigingen uitgevoerd.']);
        }

        $vinyl->save();

        return redirect()->route('vinyls.show', $vinyl);
    }

    public function favorite(Vinyl $vinyl){
        $favorites = $vinyl->favorites()->where('user_id', auth()->id());

        if ($favorites->exists()) {
            $favorites->delete();
        } else {
            $vinyl->favorites()->create(['user_id' => auth()->id()]);
        }

        return back();
    }

    public function overview(){
        $vinyls = Vinyl::all();

        return view('vinyls.overview', ['vinyls' => $vinyls]);
    }


    public function destroy(Vinyl $vinyl)
    {
        $user = Auth::user();
        abort_unless($vinyl->created_by == auth()->id() or $user->admin, 403);

        $vinyl->delete();

        return redirect('/');
    }
}

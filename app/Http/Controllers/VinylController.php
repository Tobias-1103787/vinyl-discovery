<?php

namespace App\Http\Controllers;

use App\Services\DiscogsService;
use Illuminate\Http\Request;
use Calliostro\Discogs\DiscogsClientFactory;

use Illuminate\Support\Facades\DB;

class VinylController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //DB::insert('insert into vinyl ()');
        //TEMP
        return view('create', ['vinyl' => null]);
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

            return view('create', ['vinyl' => $release]);
        }

        //title search
        $results = collect($discogs->search($input)['results'] ?? [])
            ->filter(fn ($r) => preg_match('#/(master|release)/#i', $r['uri'] ?? ''))
            ->filter(fn ($r) => $discogsService->isVinylResult($r))
            ->values()
            ->all();

        return view('create', ['vinyl' => null, 'results' => $results]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //aanmaken van nieuwe vinyl

        return view('index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

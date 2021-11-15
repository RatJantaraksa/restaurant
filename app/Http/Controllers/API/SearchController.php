<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->query('keyword', 'Bang sue');

        $results = Cache::get($keyword, function () use ($keyword) {

            // Find location of search area
            $endpoint = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json";
            $response = Http::get($endpoint, [
                'input' => $keyword,
                'inputtype' => 'textquery',
                'locationbias' => 'circle@13.8186419,100.5386657',
                'fields' => 'formatted_address,name,geometry',
                'key' => env('API_KEY')
            ]);
    
            $response = $response->object();
    
            if($response->status !== 'OK'){
                return response()->json([]);
            }
            
            $location = $response->candidates[0]->geometry->location;
            
            // Find restaurant of area
            $endpoint = "https://maps.googleapis.com/maps/api/place/nearbysearch/json";
            $response = Http::get($endpoint, [
                'location' => $location->lat.','.$location->lng,
                'radius' => 2000,
                'type' => 'restaurant',
                'key' => env('API_KEY')
            ]);
            
            $response = $response->object();
    
            if($response->status == 'OK'){
                return response()->json($response->results);
            }

        });

        return $results;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

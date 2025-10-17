<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class IcdController extends Controller
{

    // ==========================
    // ICD-10
    // ==========================
    public function icd10(Request $request)
    {
        $search = $request->get('q');

        $data = DB::table('icd10_codes')
            ->select('code', 'description', 'system', 'validcode', 'accpdx', 'asterisk', 'im')
            ->when($search, function ($query, $search) {
                $query->where('code', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            })
            ->limit(30)
            ->get();

        return response()->json($data->map(fn($d) => [
            'id' => $d->code,
            'code' => $d->code,
            'description' => $d->description,
            'system' => $d->system,
            'validcode' => $d->validcode,
            'accpdx' => $d->accpdx,
            'asterisk' => $d->asterisk,
            'im' => $d->im,
            'text' => "{$d->code} - {$d->description}",
        ]));
    }

    // ==========================
    // ICD-9 (Prosedur)
    // ==========================
    public function icd9(Request $request)
    {
        $search = $request->get('q');

        $data = DB::table('icd9cm_codes')
            ->select('code', 'description', 'system', 'validcode', 'accpdx', 'asterisk', 'im')
            ->when($search, function ($query, $search) {
                $query->where('code', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            })
            ->limit(30)
            ->get();

        return response()->json($data->map(fn($d) => [
            'id' => $d->code,
            'code' => $d->code,
            'description' => $d->description,
            'system' => $d->system,
            'validcode' => $d->validcode,
            'accpdx' => $d->accpdx,
            'asterisk' => $d->asterisk,
            'im' => $d->im,
            'text' => "{$d->code} - {$d->description}",
        ]));
    }




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

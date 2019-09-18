<?php

namespace App\Http\Controllers;

use App\FicheType;
use App\Http\Resources\FicheTypeResource;
use Response;

class FicheTypeController extends Controller
{

    public function index()
    {

        return FicheTypeResource::collection(FicheType::get());
    }

    public function store(Request $request)
    {

        $ficheType = FicheType::create([
            'name' => $request->name,
        ]);

        return response()->json($ficheType);
    }

    public function show($id)
    {

        $ficheType = FicheType::firstOrFail($id);

        return response()->json($ficheType);
    }

    public function edit($id)
    {
        $ficheType = FicheType::firstOrFail($id);

        return response()->json($ficheType);
    }

    public function update(Request $request, $id)
    {
        $ficheType = FicheType::where('id', $id)->update([
            'name' => $request->name,
        ]);

        return response()->json($ficheType);
    }

    public function destroy($id)
    {
        $ficheType = FicheType::findOrFail($id)->delete();

        return response()->json($ficheType);
    }
}

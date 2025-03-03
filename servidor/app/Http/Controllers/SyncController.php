<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function sync(Request $request)
    {
        // $request->validate([
        //     'items' => 'required|array',
        //     'items.*.id' => 'required|integer|exists:items,id',
        //     'items.*.name' => 'required|string',
        //     'items.*.updated_at' => 'required|date',
        // ]);

        // foreach ($request->items as $data) {
        //     $item = Item::find($data['id']);

        //     if ($item->updated_at < $data['updated_at']) {
        //         $item->update(['name' => $data['name']]);
        //     }
        // }

        return response()->json(['message' => 'Dados sincronizados com sucesso']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Attribute;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required|string|in:text,date,number,select',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $attribute = Attribute::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return response()->json($attribute, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required|string|in:text,date,number,select',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json(['message' => 'Attribute not found'], 404);
        }

        $attribute->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return response()->json($attribute);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::with('attributeValues.attribute')->get();

        return response()->json($projects);
    }

    public function show($id)
    {
        $project = Project::with('attributeValues.attribute')->findOrFail($id);

        return response()->json($project);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required|string',
            'attributes' => 'nullable|array',
            'attributes.*.attribute_id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $project = Project::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        if ($request->has('attributes')) {
            foreach ($request->attributes as $attribute) {
                $project->attributeValues()->create([
                    'attribute_id' => $attribute['attribute_id'],
                    'value' => $attribute['value'],
                ]);
            }
        }

        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required|string',
            'attributes' => 'nullable|array',
            'attributes.*.attribute_id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $project = Project::findOrFail($id);
        $project->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        if ($request->has('attributes')) {
            $project->attributeValues()->delete();

            foreach ($request->attributes as $attribute) {
                $project->attributeValues()->create([
                    'attribute_id' => $attribute['attribute_id'],
                    'value' => $attribute['value'],
                ]);
            }
        }

        return response()->json($project);
    }

    public function createAttribute(Request $request)
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

    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $projects = Project::whereHas('attributeValues', function ($query) use ($request) {
            $query->where('attribute_id', $request->attribute_id)
                ->where('value', 'like', '%' . $request->value . '%');
        })->get();

        return response()->json($projects);
    }
}

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
        /**
         * This will be used to get the filters.
         * By default all filters will use the "=" operator.
         *
         * The syntax to pass a filter will be projects?filters[name]=Project1. This should have the exact field name.
         *
         * For the use of different operators, pass the name of the field followed by _operator and then the operator itself
         * projects?filters[name]=Project1&filters[name_operator]=LIKE
         *
         * The possible operators are >, <, gt, lt, LIKE and like
         */

        $filters = $request->get('filters', []);

        $projects = Project::filter($filters)->with('attributeValues.attribute')->get();

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
            'attrs' => 'nullable|array',
            'attrs.*.attribute_id' => 'required|exists:attributes,id',
            'attrs.*.value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $project = Project::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);


        if ($request->has('attrs')) {
            foreach ($request->attrs as $attribute) {
                $project->attributeValues()->create([
                    'attribute_id' => $attribute['attribute_id'],
                    'value' => $attribute['value'],
                ]);
            }
        }

        $project->load('attributeValues.attribute');

        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required|string',
            'attrs' => 'nullable|array',
            'attrs.*.attribute_id' => 'required|exists:attributes,id',
            'attrs.*.value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $project = Project::findOrFail($id);
        $project->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        if ($request->has('attrs')) {
            $project->attributeValues()->delete();

            foreach ($request->attrs as $attribute) {
                $project->attributeValues()->create([
                    'attribute_id' => $attribute['attribute_id'],
                    'value' => $attribute['value'],
                ]);
            }
        }

        $project->load('attributeValues.attribute');

        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->attributeValues()->delete();
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully'], 200);
    }
}

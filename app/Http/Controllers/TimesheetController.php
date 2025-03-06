<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->get('filters', []);

        $timesheets = Timesheet::filter($filters)->get();
        return response()->json($timesheets);
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'date' => 'required|date',
            'hours' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $timesheet = Timesheet::create($request->all());

        return response()->json($timesheet->id, 201);
    }

    public function show(Timesheet $timesheet)
    {
        return response()->json($timesheet);
    }

    public function update(Request $request, Timesheet $timesheet)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'date' => 'required|date',
            'hours' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $timesheet->update($request->all());

        return response()->json($timesheet);
    }

    public function destroy(Timesheet $timesheet)
    {
        $timesheet->delete();

        return response()->json(['message' => 'Timesheet deleted successfully'], 204);
    }
}

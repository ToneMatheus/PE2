<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaderRelation;
use App\Models\User;

class RelationsController extends Controller
{
    public function fetchRelations()
{
    $relations = LeaderRelation::with(['leader', 'employee'])->get()->map(function ($relation) {
        return [
            'id' => $relation->id,
            'leader_name' => $relation->leader->first_name . ' ' . $relation->leader->last_name,
            'employee_name' => $relation->employee->first_name . ' ' . $relation->employee->last_name,
            'relation' => $relation->relation,
        ];
    });

    return response()->json($relations);
}


    public function updateRelation(Request $request)
    {
        $request->validate([
            'relationId' => 'required|integer',
            'newLeaderId' => 'required|integer',
        ]);

        $relation = LeaderRelation::find($request->relationId);
        if ($relation) {
            $relation->leader_id = $request->newLeaderId;
            $relation->save();
            return response()->json(['message' => 'Relation updated successfully.']);
        }

        return response()->json(['message' => 'Relation not found.'], 404);
    }
}

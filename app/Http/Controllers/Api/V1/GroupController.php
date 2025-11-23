<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{

    public function index()
    {
        try {
            $groups = Group::with('project')->get();
            return response()->json([
                'success' => true,
                'message' => 'Data grup berhasil diambil !',
                'data' => $groups
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data grup gagal diambil !',
                'data' => []
            ]);
        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'project_id' => 'required|exists:projects,id',
                'name' => 'required|string|max:255',
            ]);
    
            $group = Group::create([
                'project_id' => $request->project_id,
                'name' => $request->name,
                'max_members' => $request->max_members ?? 5,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Group berhasil dibuat !',
                'data' => $group
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Group gagal dibuat !',
                'data' => []
            ]);
        }
    }
}

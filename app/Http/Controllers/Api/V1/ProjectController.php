<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            $projects = Project::with('classroom')
                        ->whereHas('classroom', function ($q) {
                            $q->where('teacher_id', Auth::id());
                        })
                        ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data tugas berhasil diambil !',
                'data' => $projects
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tugas gagal diambil !',
                'data' => []
            ]);
        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'classroom_id' => 'required|exists:classrooms,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'total_points' => 'nullable|integer',
                'deadline' => 'nullable|date',
            ]);

            $project = Project::create([
                'classroom_id' => $request->classroom_id,
                'name' => $request->name,
                'description' => $request->description,
                'total_points' => $request->total_points ?? 0,
                'deadline' => $request->deadline,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dibuat !',
                'data' => $project
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Tugas gagal dibuat !',
                'data' => []
            ]);
        }
    }
}

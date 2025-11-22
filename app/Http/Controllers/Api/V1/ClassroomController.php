<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    public function index()
    {
        try {
            $classrooms = Classroom::where('teacher_id', Auth::user()->id)->get();
            return response()->json([
                
                'success' => true,
                'message' => 'Data kelas berhasil diambil !',
                'data' => $classrooms
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data kelas gagal diambil !',
                'data' => []
            ]);
        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);
    
            $classroom = Classroom::create([
                'name' => $request->name,
                'teacher_id' => Auth::user()->id
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dibuat !',
                'data' => $classroom
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Kelas gagal dibuat !',
                'data' => []
            ]);
        }
    }

    public function joinClass(Request $request)
    {
        try {
            $request->validate([
                'class_code' => 'required|string|exists:classrooms,class_code',
            ]);

            $classroom = Classroom::where('class_code', $request->class_code)->first();
            if (!$classroom) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan !',
                    'data' => []
                ], 404);
            }
            
            $classroom->students()->attach(Auth::user()->id, ['joined_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil bergabung ke kelas !',
                'data' => []
            ]);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal bergabung ke kelas !',
                'data' => []
            ]);
        }
    }

}

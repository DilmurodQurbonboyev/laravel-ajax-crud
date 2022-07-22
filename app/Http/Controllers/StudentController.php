<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        return view('students.index');
    }

    public function fetchstudent()
    {
        $students = Student::all();
        return response()->json(
            [
                'students' => $students,
            ]
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'course' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 400,
                    'errors' => $validator->messages(),
                ]
            );
        } else {
            $student = new Student();
            $student->name = $request->input('name');
            $student->email = $request->input('email');
            $student->phone = $request->input('phone');
            $student->course = $request->input('course');
            $student->save();

            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Student added successfully',
                ]
            );
        }
    }


    public function edit($id)
    {
        $student = Student::query()->findOrFail($id);
        if ($student) {
            return response()->json(
                [
                    'status' => 200,
                    'student' => $student,
                ]
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No student found'

                ]
            );
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'course' => 'required|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|max:10|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 400,
                    'errors' => $validator->messages()
                ]
            );
        } else {
            $student = Student::query()->findOrFail($id);
            if ($student) {
                $student->name = $request->input('name');
                $student->course = $request->input('course');
                $student->email = $request->input('email');
                $student->phone = $request->input('phone');
                $student->update();
                return response()->json(
                    [
                        'status' => 200,
                        'message' => 'Student updated successfully'
                    ]
                );
            } else {
                return response()->json(
                    [
                        'status' => 404,
                        'message' => 'No student found'
                    ]
                );
            }

        }
    }

    public function destroy($id)
    {
        $student = Student::query()->findOrFail($id);
        if ($student) {
            $student->delete();
            return response()->json(
                [
                    'status' => 200,
                    'message' => 'Student deleted successfully'
                ]
            );
        } else {
            return response()->json(
                [
                    'status' => 404,
                    'message' => 'No student found'
                ]
            );
        }
    }
}

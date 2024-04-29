<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Students/Index', [
            'students' => Student::all(),
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Students/Create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:1024',
            'age' => 'required|integer',
            'status' => 'boolean',
        ]);

        $image = $request->file('image') ? Storage::putFile('students', $validatedData['image']) : null;

        $student = Student::create([
            'name' => $validatedData['name'],
            'image' => $image,
            'age' => $validatedData['age'],
            'status' => $validatedData['status'],
        ]);

        return redirect()->route('students.index')->with('message', 'Student record successfully created');
    }

    public function edit(Student $student)
    {
        return Inertia::render('Students/Edit', [
            'student' => $student,
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:1024',
            'age' => 'required|integer',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            Storage::delete($student->image);
            $image = Storage::putFile('students', $validatedData['image']);
            $student->image = $image;
        }

        $student->name = $validatedData['name'];
        $student->age = $validatedData['age'];
        $student->status = $validatedData['status'];
        $student->save();

        return redirect()->route('students.index')->with('message', 'Student record successfully updated');
    }

    public function destroy(Student $student)
    {
        if ($student->image) {
            Storage::delete($student->image);
        }

        $student->delete();

        return redirect()->route('students.index')->with('message', 'Student record successfully deleted');
    }
}

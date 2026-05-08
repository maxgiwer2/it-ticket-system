<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('type')->orderBy('name')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:faculty,medical_center',
            'name' => [
                'required',
                'max:255',
                Rule::unique('departments')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type);
                }),
            ],
        ]);

        Department::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => 'active',
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'เพิ่มหน่วยงานเรียบร้อยแล้ว');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'type' => 'required|in:faculty,medical_center',
            'status' => 'required|in:active,inactive',
            'name' => [
                'required',
                'max:255',
                Rule::unique('departments')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type);
                })->ignore($department->id),
            ],
        ]);

        $department->update($request->only('name', 'type', 'status'));

        return redirect()->route('admin.departments.index')->with('success', 'แก้ไขข้อมูลหน่วยงานเรียบร้อยแล้ว');
    }

    public function destroy(Department $department)
    {
        // For safety, maybe just deactivate instead of delete if tickets exist
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'ลบหน่วยงานเรียบร้อยแล้ว');
    }
}

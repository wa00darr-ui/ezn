<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'grade' => ['nullable', 'string', 'max:100'],
            'classroom' => ['nullable', 'string', 'max:30'],
            'status' => [
                'nullable',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        $students = Student::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%")
                        ->orWhere('guardian_name', 'like', "%{$search}%");
                });
            })
            ->when($filters['grade'] ?? null, fn ($q, $value) =>
                $q->where('grade', $value)
            )
            ->when($filters['classroom'] ?? null, fn ($q, $value) =>
                $q->where('classroom', $value)
            )
            ->when($filters['status'] ?? null, fn ($q, $value) =>
                $q->where('status', $value)
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $grades = Student::query()
            ->distinct()
            ->orderBy('grade')
            ->pluck('grade');

        $classrooms = Student::query()
            ->distinct()
            ->orderBy('classroom')
            ->pluck('classroom');

        return view('dashboard.students.index', compact(
            'students',
            'grades',
            'classrooms',
            'filters'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_number' => [
                'required', 'string', 'max:50',
                Rule::unique('students', 'student_number'),
            ],
            'national_id' => [
                'required', 'digits:10',
                Rule::unique('students', 'national_id'),
            ],
            'grade' => ['required', 'string', 'max:100'],
            'classroom' => ['required', 'string', 'max:30'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_phone' => [
                'required', 'string', 'max:25',
            ],
            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        DB::transaction(function () use ($data) {
            Student::create($data);
        });

        return redirect()
            ->route('dashboard.students.index')
            ->with('success', 'تمت إضافة الطالب بنجاح.');
    }

    public function show(Student $student): View
    {
        $requests = $student->permissionRequests()
            ->latest('created_at')
            ->paginate(10);

        return view('dashboard.students.show', compact(
            'student',
            'requests'
        ));
    }

    public function update(
        Request $request,
        Student $student
    ): RedirectResponse {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_number' => [
                'required', 'string', 'max:50',
                Rule::unique('students', 'student_number')
                    ->ignore($student->id),
            ],
            'national_id' => [
                'required', 'digits:10',
                Rule::unique('students', 'national_id')
                    ->ignore($student->id),
            ],
            'grade' => ['required', 'string', 'max:100'],
            'classroom' => ['required', 'string', 'max:30'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_phone' => [
                'required', 'string', 'max:25',
            ],
            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        $student->update($data);

        return redirect()
            ->route('dashboard.students.index')
            ->with('success', 'تم تحديث بيانات الطالب بنجاح.');
    }
}


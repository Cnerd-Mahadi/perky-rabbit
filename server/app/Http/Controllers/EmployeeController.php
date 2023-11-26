<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\AchievementEmployee;
use App\Models\Department;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class EmployeeController extends Controller
{
    public function create(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'phone' => 'required',
                'address' => 'required',
                'department_id' => 'required|exists:departments,id',
                'achievements' => 'required|array',
                'achievements.*' => 'required',
                'achievements.*.id' => 'required|exists:achievements,id',
                'achievements.*.date' => 'required',
            ]
        );
        if ($validation->fails())
            return response()->json([
                'error' => $validation->errors(),
            ]);

        try {
            $employee = new Employee();
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->address = $request->address;
            $employee->department_id = $request->department_id;
            $employee->save();

            try {
                foreach ($request->achievements as $achievement) {
                    $pivot = new AchievementEmployee();
                    $pivot->employee_id = $employee->id;
                    $pivot->achievement_id = $achievement['id'];
                    $pivot->achievement_date = $achievement['date'];
                    $pivot->save();
                }
            } catch (Exception $e) {
                return response()->json([
                    'error' => "Achievement association failed",
                    'message' => $e->getMessage(),
                ], 500);
            }

            return response()->json(['message' => 'Employee created successfully'], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => "Employee creation failed",
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    public function update(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($request->id),
                ],
                'phone' => 'required',
                'address' => 'required',
                'department_id' => 'required|exists:departments,id',
                'achievements' => 'required|array',
                'achievements.*' => 'required',
                'achievements.*.id' => 'required|exists:achievements,id',
                'achievements.*.date' => 'required',
            ]
        );
        if ($validation->fails())
            return response()->json([
                'error' => $validation->errors(),
            ]);

        try {
            $employee = Employee::find($request->id);
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->address = $request->address;
            $employee->department_id = $request->department_id;
            $employee->save();

            try {

                AchievementEmployee::where('employee_id', $employee->id)->delete();
                foreach ($request->achievements as $achievement) {
                    $pivot = new AchievementEmployee();
                    $pivot->employee_id = $employee->id;
                    $pivot->achievement_id = $achievement['id'];
                    $pivot->achievement_date = $achievement['date'];
                    $pivot->save();
                }
            } catch (Exception $e) {
                return response()->json([
                    'error' => "Achievement association failed",
                    'message' => $e->getMessage(),
                ], 500);
            }

            return response()->json(['message' => 'Employee updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => "Employee update failed",
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    public function getAll()
    {
        try {
            $employees = Employee::with(['achievements', 'department'])->get();
            return response()->json(['employees' => $employees], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => "Employees retrieval failed",
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function get(Request $request)
    {
        try {
            $employee = Employee::with(['achievements', 'department'])
                ->where(
                    'id',
                    $request->employee_id
                )->first();
            return response()->json(['employees' => $employee], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => "Employee retrieval failed",
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCategories()
    {
        try {
            $departments = Department::all();
            $achievements = Achievement::all();
            return response()->json(['departments' => $departments, 'achievements' => $achievements], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => "Categories retrieval failed",
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function filteredByDept(Request $request)
    {
        try {
            $employees = Employee::with(['achievements', 'department'])->where('department_id', $request->department_id)->get();
            return response()->json(['employees' => $employees], 200);


        } catch (Exception $e) {
            return response()->json([
                'error' => "Filtered employees retrieval failed",
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function filteredByAchievement(Request $request)
    {
        try {
            $employees = Achievement::with(['employees.achievements', 'employees.department'])->where('id', $request->achievement_id)->get();

            return response()->json(['employees' => $employees], 200);


        } catch (Exception $e) {
            return response()->json([
                'error' => "Filtered employees retrieval failed",
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

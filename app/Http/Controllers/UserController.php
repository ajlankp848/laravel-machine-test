<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hobby;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $hobbies    = Hobby::fetchAllHobbies();
        $categories = Category::fetchAllCategories();
        return view('user-view', compact('hobbies', 'categories'));
    }

    public function getUsersData()
    {
        $users = User::fetchAllUsers();
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('hobbies', function ($user) {
                $hobbies = explode(',', $user->hobbies_id);
                return Hobby::whereIn('id', $hobbies)->pluck('hobby_name')->implode(', ');
            })
            ->addColumn('category', function ($user) {
                return $user->category ? $user->category->category_name : '';
            })
            ->addColumn('profile_photo', function ($user) {
                return $user->profile_photo ? '<img src="' . asset($user->profile_photo) . '" alt="Profile Pic" style="width: 50px; height: 50px;">' : 'No Image';
            })
            ->addColumn('actions', function ($user) {
                return '<button class="btn btn-sm btn-primary btn-edit">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete" data-user_id="' . $user->id . '">Delete</button>';
            })
            ->rawColumns(['profile_photo', 'actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = User::validateAttributes($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = $request->all();
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo');
        }
        $user = User::createUser($data);
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $validator = User::validateAttributesEdit($request->all());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $data = $request->all();
        if ($request->hasFile('edit_profile_photo')) {
            $data['edit_profile_photo'] = $request->file('edit_profile_photo');
        }
        $user = User::updateUser($data);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => 'User deleted successfully!']);
    }
}

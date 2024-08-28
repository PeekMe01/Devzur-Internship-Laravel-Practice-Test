<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    protected $userModel;

    // Constructor Dependency Injection
    public function __construct(
        User $user,
    ) {
        $this->userModel = $user;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = $this->userModel::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(10); // Adjust pagination as needed

        return view('admin/users/users', compact('users'));
    }
}

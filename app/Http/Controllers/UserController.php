<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Mail\NewUserNotification;
use App\Mail\Welcome;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;

    public function createUser(CreateUserRequest $request)
    {
        try {
            $user = User::create($request->all());
            
            // Queue the welcome email
            Mail::to($user->email)->queue(new Welcome($user));
            Mail::to(config('mail.admin_email'))->queue(new NewUserNotification($user));

            return $this->successResponse($user, 'User created successfully');
            
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getUsers(Request $request)
    {
        $query = User::query()->where('active', true);
        
        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Handle sorting
        $sortBy = $request->input('sortBy', 'created_at'); // default sort by created_at
        if (in_array($sortBy, ['name', 'email', 'created_at'])) {
            $query->orderBy($sortBy);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Handle pagination
        $page = max(1, $request->input('page', 1)); // ensure page is at least 1
        $perPage = 10; // items per page
        
        $users = $query->withCount("orders")->paginate($perPage, ['*'], 'page', $page);
        
        return $this->successResponse([
            'page' => $users->currentPage(),
            'data' => $users->items(),
        ]);
    }
}

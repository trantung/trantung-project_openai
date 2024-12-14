<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use DB;

class UserController extends Controller
{
    private $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $users = $this->user->paginate(10);
        return view('admin.user.home', compact('users'));
    }
 
    public function create()
    {
        return view('admin.user.create');
    }
 
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $user = $this->user->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => $request->role
            ]);
            DB::commit();
            session()->flash('success', 'User created successfully');
            return redirect()->route('admin/users');
        }catch(\Exception $exception){
            DB::rollBack();
            Log::error('Message: ' . $exception->getMessage() . 'Line: ' . $exception->getLine());
            session()->flash('error', 'Some problem occurred');
            return redirect(route('admin/users/create'));
        }
    }
    public function edit($id)
    {
        $user = $this->user->find($id);
        return view('admin.user.update', compact('user'));
    }
 
    public function delete($id)
    {
        $user = $this->user->find($id)->delete();
        if ($user) {
            session()->flash('success', 'User Deleted Successfully');
            return redirect(route('admin/users'));
        } else {
            session()->flash('error', 'User Not Delete successfully');
            return redirect(route('admin/users'));
        }
    }
 
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
    
            // Start building the update data array
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'type' => $request->role
            ];
    
            // Check if a new password is provided and is not empty
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
    
            // Perform the update operation
            $user = $this->user->find($id)->update($updateData);
    
            DB::commit();
            session()->flash('success', 'User updated successfully');
            return redirect(route('admin/users'));
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Message: ' . $exception->getMessage() . ' Line: ' . $exception->getLine());
            session()->flash('error', 'Some problem occurred');
            return redirect(route('admin/users/update'));
        }
    }

}

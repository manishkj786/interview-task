<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Traits\ResponseTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
class UsersController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $title = 'Users ';

        return view('users.index',compact('title'));
    }

    public function fetchUserList(Request $request)
    {

        try{
            $data = User::with('role')->latest()->get();
            return response()->json(['message'=>'Data Retrived Successfully!','status'=>'success','data'=>$data], 200);
        }catch(\Exception $e)
        {
            return response()->json(['message'=>'Something Went Wrong!','status'=>'error'], 200);
        }
    }

    public function storeUser(Request $request)
    {


        try{

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'phone' => 'required|numeric|regex:/^[6789]\d{9}$/',
                'description' => 'required|string',
                'role' => 'required', // Assuming roles are limited to 1, 2, and 3
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048', // Assuming image upload
            ]);

            if ($validator->fails()) {

                return response()->json(['message'=>$validator->errors()->first(),'status'=>'error'], 200);
            }


            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
            $data = $request->all();
            $data['profile_image'] = $imageName;
            unset($data['image']);
            $user = User::create($data);

            return response()->json(['message'=>'Form Submitted Successfully!','status'=>'success'], 200);
        }catch(\Exception $e)
        {
            return response()->json(['message'=>'Something Went Wrong!','status'=>'error'], 200);
        }
    }
}

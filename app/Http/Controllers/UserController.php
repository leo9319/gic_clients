<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\CounsellorClient;
use App\RmClient;
use App\User;
use Storage;

class UserController extends Controller
{
    public function edit($id)
    {
        $data['user'] = User::find($id);

        return view('users.edit', $data);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'unique:users,email,'.$request->client_id,
        ]);

        $filename = '';

        if($request->hasFile('profile_picture')) {

            // Delete the already created file in the user table

            $profile_picture_name = User::find($request->client_id)->profile_picture;

            if(Storage::exists('upload/profile_pictures/'. $profile_picture_name)){
                Storage::delete('upload/profile_pictures/' . $profile_picture_name);
            }
  
            $profile_picture = $request->file('profile_picture');
            $filename = time() . '_' . $profile_picture->getClientOriginalName();

            Storage::put('upload/profile_pictures/' . $filename, file_get_contents($request->file('profile_picture')->getRealPath()));

        }

        User::find($request->client_id)
            ->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'profile_picture' => $filename,
            ]);

        return back();

    }

    public function myclients()
    {
    	$data['active_class'] = 'my-clients';

    	if(Auth::user()->user_role == 'counselor') {
    		$counselor_id = Auth::user()->id;
    		$data['assigned_clients'] = CounsellorClient::where('counsellor_id', $counselor_id)->get();

    		return view('users.client', $data);
    	}
    	else if (Auth::user()->user_role == 'rm')
    	{
    		$rm_id = Auth::user()->id;
    		$data['assigned_clients'] = RmClient::where('rm_id', $rm_id)->get();

    		return view('users.client', $data);
    	}
    }

    public function getClientInfo(Request $request)
    {
        $data = User::find($request->client_id);

        return response()->json($data);
    }


}

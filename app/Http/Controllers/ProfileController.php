<?php

namespace App\Http\Controllers;

use App\User;
use App\UserDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    private $id;

    public function index() {
        $user = Auth::user()->detail;

        $image = asset('assets/images/user/'.Auth::user()->detail->image);

        $data = [
            $user,
            $image
        ];

        return apiResponse(200, 'success', 'Data profil anda', $data);
    }

    public function update(Request $request) {
        $this->id = Auth::user()->id;

        $rules = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$this->id,
            'address'   => 'required',
            'phone'     => 'required',
            'hobby'     => 'required',
        ];

        $message = [
            'name.required'     => 'Mohon isikan nama anda',
            'email.required'    => 'Mohon isikan email anda',
            'email.email'       => 'Mohon isikan email valid',
            'email.unique'      => 'Email sudah terdaftar',
            'password.min'      => 'Password wajib mengandung minimal 8 karakter',
            'address.required'  => 'Mohon isikan alamat anda',
            'phone.required'    => 'Mohon isikan nomor hp anda',
            'hobby.required'    => 'Mohon isikan hobi anda',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if($validator->fails()) {
            return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
        }

        try {
            DB::transaction(function () use ($request) {
                User::where('id', $this->id)->update([
                    'name'  => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                UserDetail::where('user_id', $this->id)->update([
                    'address'       => $request->address,
                    'phone'         => $request->phone,
                    'hobby'         => $request->hobby,
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);

                if($request->has('image')) {
                    $oldImage = Auth::user()->detail->image;

                    if($oldImage) {
                        $pleaseRemove = base_path('public/assets/images/user/').$oldImage;

                        if(file_exists($pleaseRemove)) {
                            unlink($pleaseRemove);
                        }
                    }

                    $extension = $request->file('image')->getClientOriginalExtension();

                    $name = date('YmdHis').''.$this->id.'.'.$extension;

                    $path = base_path('public/assets/images/user');

                    $request->file('image')->move($path, $name);

                    UserDetail::where('user_id', $this->id)->update([
                        'image' => $name,
                    ]);
                }
            });

            return apiResponse(202, 'success', 'profile berhasil disunting');
        } catch(Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }
}

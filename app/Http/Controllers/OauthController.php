<?php

namespace App\Http\Controllers;

use Auth;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

use App\Models\User;

use Illuminate\Http\Request;

class OauthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleProviderCallback()
    {
        try {

            $user = Socialite::driver('google')->user();

            $finduser = User::where('gauth_id', $user->id)->first();

            if ($finduser && $finduser->verified) {
                Auth::login($finduser);
                return redirect('/');

            } elseif ($finduser) {
                // toast('Menunggu Konfirmasi Admin', 'success');
                Alert::success('Success', 'Menunggu Konfirmasi Admin');
                return redirect('/login');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'gauth_id' => $user->id,
                    'gauth_type' => 'google',
                    'password' => bcrypt('4dm1nflasy')
                ]);

                $role = Role::where('name', 'Volunteer')->first();
                $newUser->assignRole($role);

                Alert::success('Success', 'Sukses terdaftar, Menunggu Konfirmasi Admin');
                return redirect('/login');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

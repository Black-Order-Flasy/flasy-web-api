<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
class VolunteerController extends Controller
{
    public function index()
    {
        $user = User::role('Volunteer')->get();
        return view('volunteer', [
            'user' => $user
        ]);
    }
    public function verify(Request $request, $id)
    {
        $user = User::find($id);
        $method = $request->input;

        if ($method == 'verify') {
            $user->verified = 1;
            $user->save();
        } elseif ($method == 'decline') {
            $user->delete();
        }
    
        // Tambahkan respon atau redirect sesuai kebutuhan
        Alert::success('Success', 'Volunteer Verified');
        return redirect()->route('volunteer.index');

    }
}

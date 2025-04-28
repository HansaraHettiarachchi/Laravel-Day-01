<?php

namespace App\Http\Controllers;

use App\Models\NewUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewUserController extends Controller
{
    function submitLogin(Request $request)
    {
        $email = $request->input('email');
        $ps = $request->input('ps');

        $rs = NewUser::where('password', $ps)->where('email', $email)->first();

        if ($rs) {

            session(['user' => $rs]);

            return "User Found";
        } else {
            return "User Not Found";
        }
    }

    function userList()
    {

        $userList =   NewUser::all();
        // view('asdasd',compact('userList'));
        return view('userList', compact("userList"));
    }

    function createUser(Request $req)
    {
        $email = $req->input("e");
        $ps = $req->input("ps");
        $name = $req->input("n");
        $add = $req->input("add");

        $user = new NewUser();
        $user->name = $name;
        $user->email = $email;
        $user->password = $ps;
        $user->address = $add;
        $user->save();

        return "User Created Successfully";
    }

    function editUser(Request $req)
    {
        $id = $req->input("id");

        $userData = NewUser::find($id);

        if ($userData) {
            return view('editUser', compact("userData"));
        }
    }

    function updateUser(Request $req)
    {

        // $validator = Validator::make($req->all(), [
        //     'uid' => 'required',
        //     'name' => 'required|string|max:50',
        //     'email' => 'required|email|unique:NewUsers',
        //     'password' => 'required|min:10',
        //     'address' => 'required|stirng|min:100',
        // ]);

        $data = $req->all();

        $user = NewUser::find($data["uid"]);

        $user->name = $data["name"];
        $user->password = $data["password"];
        $user->address = $data["address"];
        $user->email = $data["email"];

        $user->save();

        return "User Updated Successful";
    }

    function deleteUser(Request $req)
    {
        $user = NewUser::find($req->input("id"));
        $user->status = "Deactive";
        $user->save();
    }

    function userData(Request $req)
    {
        $data = $req->all();

        $total = NewUser::count();
        $filtered = NewUser::where('status', "Active")->count();

        $users = NewUser::where('status', "Active")
            ->offset($data["start"])
            ->limit($data["length"])
            ->get();

        $arr = [];

        foreach ($users as $key => $user) {
            $arr[] = [
                "Index" => $key + 1 + $data["start"], // Correct index with pagination
                "Name" => $user->name,
                "Email" => $user->email,
                "Password" => $user->password,
                "Address" => $user->address,
                "Action" => '<button class="btn btn-primary editBtn" data-id="' . $user->id . '">Edit</button>',
                "Status" => '<button class="btn btn-danger deleteBtn" data-id="' . $user->id . '">Delete</button>'
            ];
        }

        return response()->json([
            'draw' => $req->input('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $arr
        ]);
    }
}

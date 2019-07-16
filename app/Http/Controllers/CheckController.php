<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\User;
class CheckController extends Controller
{
    public function checkEmail(Request $request){
        $email = $request->input('email');
        $isExists =Customer::where('email',$email)->first();
        if($isExists){
            return response()->json(array("exists" => true));
        }else{
            return response()->json(array("exists" => false));
        }
    }
}

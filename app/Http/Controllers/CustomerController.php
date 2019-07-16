<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;
use Hash;
class CustomerController extends Controller
{
    public function register(CustomerRequest $request){
        $customer = new Customer();
        $customer->username = $request->username;
        $customer->email = $request->email;
        $customer->password  = Hash::make($request->password);
        $customer->token = $request->_token;

        $customer->save();
        return redirect()->route('register')->with('success', 'Success! Register Complete');
    }
}

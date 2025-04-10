<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller {
    public function index()
    {
        return view('admin.adminPanel');
    }

    public function create()
    {
        // Logic to create a new admin
    }

    public function edit($id)
    {
        // Logic to edit an existing admin
    }

    public function destroy($id)
    {
        // Logic to delete an admin
    }
}

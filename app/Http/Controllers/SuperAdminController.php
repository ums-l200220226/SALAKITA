<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index()
    {
        return "Dashboard superadmin";
    }

    public function approval()
    {
        return "Halaman approval";
    }

    public function approve($id)
    {
        return "Aksi approve user $id";
    }
}

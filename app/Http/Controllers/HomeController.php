<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $events = [
            [
            'nama' => 'E-Fest',
            'tanggal' => '2027-06-15',
            'harga' => 50000,
            'location' => 'Jubung',
            'foto' =>'efest.jpg',
             ],
            [
            'nama' => 'Civil Day',
            'tanggal' => '2027-06-15',
            'harga' => 50000,
            'location' => 'Tanjung Bunga',
            'foto'=>'civilday.jpg',
            ],
        ];
        return view('home', compact('events'));
    }
}

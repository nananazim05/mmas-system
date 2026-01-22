<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StaffImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            // Kiri: Nama column dalam Database | Kanan: Nama Header dalam Excel
            'name'      => $row['name'],
            'email'     => $row['email'],
            'ic_number' => $row['ic_number'],
            'staff_number'  => $row['staff_number'],
            'grade'     => $row['grade'],
            'section'   => $row['section'],
            'division'  => $row['division'],
            
            // Setting Default
            'role'      => 'staff', 
            'password'  => Hash::make('St@ff123'), // Default password
        ]);
    }
}

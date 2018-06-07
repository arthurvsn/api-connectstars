<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addres extends Model
{
    protected $fillable = [
        'street',
        'city',
        'state',
        'zip_code',
        'country',
        'user_id',
    ];

    public function getAddressUser($userId)
    {
        $address = DB::table('address')
            ->where('user_id', '=', $userId)
            ->get();
        
        return $address;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [
        'country_code',
        'number',
        'user_id',
    ];

    public function getPhoneUser($userId)
    {
        $address = DB::table('phone')
            ->where('user_id', '=', $userId)
            ->get();
        
        return $address;
    }
}

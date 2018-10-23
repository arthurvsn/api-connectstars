<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

use DB;

class Address extends Model
{
    use HasUuid;

    protected $fillable = [
        'street',
        'city',
        'state',
        'zip_code',
        'country',
        'number',
        'user_id',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    /**
     * references to uuid
     */
    public function getUuidColumnName()
    {
        return 'id';
    }

    /**
     * Table user relationship with addresses
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }
}

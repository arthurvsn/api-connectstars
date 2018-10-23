<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

use DB;

class Phone extends Model
{
    use HasUuid;

    protected $fillable = [
        'country_code',
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
     * Table user relationship with phones
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }
}

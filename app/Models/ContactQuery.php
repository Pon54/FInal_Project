<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactQuery extends Model
{
    protected $table = 'tblcontactusquery';
    protected $fillable = ['name','EmailId','ContactNumber','Message','PostingDate'];
    public $timestamps = false;
    
    protected $casts = [
        'PostingDate' => 'datetime',
    ];
}

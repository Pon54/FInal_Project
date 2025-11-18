<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $table = 'tblcontactusinfo';
    protected $fillable = ['Address', 'EmailId', 'ContactNo'];
    public $timestamps = false;
}
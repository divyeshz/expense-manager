<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountRequests extends Model
{
    use HasFactory;
    protected $fillable=['sender_id','account_id','is_approved','account_owner_id'];
}

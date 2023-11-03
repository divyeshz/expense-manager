<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['account_id', 'transaction_type', 'amount', 'description', 'receiver_id', 'is_transfer', 'transaction_by'];

    // Make Relationship with Category Table For Insert Data Into Pivot table
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'transaction_category', 'transaction_id', 'category_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

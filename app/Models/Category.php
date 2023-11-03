<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    // Make Relationship with Transaction Table For Insert Data Into Pivot table
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_category', 'category_id', 'transaction_id');
    }
}

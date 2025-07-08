<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'book_id',
        'account_id',
        'category_id',
        'type',
        'amount',
        'date',
        'note',
        'target_account_id',
        'is_deleted'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }

    public function account() {
        return $this->belongsTo(Account::class);
    }

    public function targetAccount() {
        return $this->belongsTo(Account::class, 'target_account_id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}

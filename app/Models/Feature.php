<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $guarded = [];

    const TYPE_LOAN = 'loan';
    const TYPE_EWALLET = 'ewallet';
    const TYPE_SALARY = 'salary';

    const ALL_FEATURES = [
        self::TYPE_LOAN,
        self::TYPE_EWALLET,
        self::TYPE_SALARY
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_enabled');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Types\Currency;

class UserCurrency extends Model
{
    protected $fillable = [
        'user_id',
        'currency_id',
        'exchange_rate_to_default_currency'
    ];

    protected $appends = ['name', 'code', 'symbol'];

    protected $hidden = ['currency', 'user'];

    protected static function booted()
    {
        static::creating(function ($userCurrency) {
            $user = Auth::user();
            if ($user) {
                $userCurrency->user_id = $user->id;
            }
        });

        static::addGlobalScope('user_id', function (Builder $builder) {
            $user = Auth::user();
            if ($user) {
                // $builder->where('user_id', Auth::id());
            }
        });
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNameAttribute()
    {
        return $this->currency->name;
    }

    public function getCodeAttribute()
    {
        return $this->currency->code;
    }

    public function getSymbolAttribute()
    {
        return $this->currency->symbol;
    }
}
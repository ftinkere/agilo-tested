<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Access
 *
 * @property-read Directory|Password $accessible
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Access newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Access newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Access query()
 * @mixin \Eloquent
 */
class Access extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'accessible_type', 'accessible_id'];

    function user() {
        return $this->belongsTo(User::class);
    }

    function accessible() {
        return $this->morphTo();
    }
}

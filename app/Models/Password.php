<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Password
 *
 * @property int $id
 * @property string $project
 * @property string $login
 * @property string $password
 * @property int|null $user_id
 * @property int|null $directory_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Directory|null $directory
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Password newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Password newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Password query()
 * @method static \Illuminate\Database\Eloquent\Builder|Password whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Password whereDirectoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Password whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Password whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Password wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Password whereProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Password whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Password whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Access> $accesses
 * @property-read int|null $accesses_count
 * @mixin \Eloquent
 */
class Password extends Model
{
    use HasFactory;

    protected $fillable = ['project', 'login', 'password', 'user_id', 'directory_id'];

    function user() {
        return $this->belongsTo(User::class);
    }

    function directory() {
        return $this->belongsTo(Directory::class);
    }

    function accesses() {
        return $this->morphMany(Access::class, 'accessible');
    }
}

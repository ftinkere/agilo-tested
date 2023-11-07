<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Directory
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Directory|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Directory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Directory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Directory query()
 * @method static \Illuminate\Database\Eloquent\Builder|Directory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Directory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Directory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Directory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Directory whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Access> $accesses
 * @property-read int|null $accesses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Password> $passwords
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Directory> $childs
 * @mixin \Eloquent
 */
class Directory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    function parent() {
        return $this->belongsTo(self::class);
    }

    function childs() {
        return $this->hasMany(self::class, 'parent_id');
    }

    function passwords() {
        return $this->hasMany(Password::class);
    }

    function contents() {
        return $this->childs()->with(['passwords', 'contents']);
    }

    function accesses() {
        return $this->morphMany(Access::class, 'accessible');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'is_leaf'
    ];

    protected $appends = [
        // '',
    ];

    protected $casts = [
        'is_leaf' => 'boolean',
        'arrows' => 'json',
    ];

    public function parents()
    {
        return $this->belongsToMany(static::class, 'group_group', 'child_id', 'parent_id');
    }

    public function children()
    {
        return $this->belongsToMany(static::class, 'group_group', 'parent_id', 'child_id');
    }

    public function algorythms()
    {
        return $this->hasMany(Algorythm::class);
    }
}

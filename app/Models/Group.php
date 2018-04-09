<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeWhereVisible(Builder $query, $isVisible = true)
    {
        $query->where('is_visible', $isVisible);
        return $query;
    }

    public function scopeWhereLeaf(Builder $query, $isLeaf = true)
    {
        $query->where('is_leaf', $isLeaf);
        return $query;
    }

    public function parents()
    {
        return $this->belongsToMany(static::class, 'group_group', 'child_id', 'parent_id');
    }

    public function children()
    {
        return $this->belongsToMany(static::class, 'group_group', 'parent_id', 'child_id');
    }

    public function algs()
    {
        return $this->hasMany(Alg::class);
    }
}

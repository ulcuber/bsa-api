<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alg extends Model
{
    protected $fillable = [
        'group_id',
        'alg',
        'is_confirmed',
    ];

    protected $appends = [
        // '',
    ];

    protected $casts = [
        'is_confirmed' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function likes()
    {
        return;
    }
}

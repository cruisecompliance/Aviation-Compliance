<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = [
        'message', 'user_id', 'rule_id'
    ];

    protected $casts = [
        'created_at' => 'date:F j, Y, g:i a',
    ];

    public function flowData()
    {
        return $this->belongsTo(FlowsData::class, 'rule_id', 'id');

    }

    public function user()
    {
        return $this->belongsTo(User::class);

    }
}

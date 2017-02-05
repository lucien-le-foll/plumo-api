<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = ['name', 'description'];

    protected $visible = ['id', 'name', 'description', 'house', 'tasks'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

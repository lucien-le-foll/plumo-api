<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = ['name', 'description'];

    protected $visible = ['id', 'name', 'description', 'house'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }
}

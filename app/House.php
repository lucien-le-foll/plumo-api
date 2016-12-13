<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    /**
     * @var string
     */
    protected $table = 'houses';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];
}

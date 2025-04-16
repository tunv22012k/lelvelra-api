<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * BaseModel
 */
abstract class BaseModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Set all columns as fillable
     *
     * @var array
     */
    protected $guarded = [];
}

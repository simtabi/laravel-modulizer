<?php

namespace Simtabi\Modules\{Module}\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Simtabi\Modules\{Module}\Database\Factories\{Model}Factory;

class {Model} extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    
    protected static function newFactory()
    {
        return {Model}Factory::new();
    }
}

<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['paper_id', 'name', 'filename'];

    protected $dates = ['created_at'];

    const UPDATED_AT = null;

    public function getUrl()
    {
        return asset('uploads/' . $this->filename);
    }

    public function formattedName()
    {
        return $this->name . '.' . Str::afterLast($this->filename, '.');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    protected $fillable = ['name', 'parent_id', 'root', 'leaf'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function isRoot(): bool
    {
        return $this->root;
    }

    public function isLeaf(): bool
    {
        return $this->leaf;
    }

    public function media()
    {
        return $this->hasOne(Media::class, 'paper_id');
    }

    public function getType()
    {
        if($this->isRoot()) {
            return 'root';
        } else if(! $this->isRoot() && ! $this->isLeaf()) {
            return 'normal';
        } else if($this->isLeaf()) {
            return 'leaf';
        }
        return 'unknown';
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Paper extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'     => (int) $this->id,
            'parent_id' => (int) $this->parent_id,
            'name'   => (string) $this->name,
            'root'   => (bool) $this->isRoot(),
            'leaf'   => (bool) $this->isLeaf(),
            'media'  => $this->media()->exists() ? new Media($this->media) : null,
        ];
    }
}

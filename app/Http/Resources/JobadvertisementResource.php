<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobadvertisementResource extends JsonResource
{
	public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            // TODO employer->name
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'location' => $this->location,
            'description' => $this->description,
            'updatedAt' => $this->updatedAt,
        ];
    }
}

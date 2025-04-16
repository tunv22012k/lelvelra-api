<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * UserResource
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = $this->resource->only([
            'id',
            'first_name',
            'last_name',
            'email',
            'sex',
            'role',
            'phone',
            'address',
            'lat',
            'lon',
            'description',
            'created_at',
            'updated_at'
        ]);

        return $resource;
    }
}

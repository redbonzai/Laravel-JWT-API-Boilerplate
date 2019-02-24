<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->id,
            'posts_id' => $this->post->id,
            'content' => $this->content,
            'reply_to' => $this->reply_to,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

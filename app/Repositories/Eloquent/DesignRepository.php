<?php

namespace App\Repositories\Eloquent;

use App\Models\Design;
use App\Repositories\Contracts\IDesign;

class DesignRepository extends BaseRepository implements IDesign
{
    public function model()
    {
        return Design::class;
    }

    public function applyTags($id, array $data)
    {
        $design = $this->find($id);
        $design->retag($data);
    }

    public function addComment($designId, array $data)
    {
        $design = $this->find($designId);
        return $design->comments()->create($data);
    }

    public function like($id)
    {
        $design =$this->model->findOrFail($id);
        if ($design->isLikedByUser(auth()->id())) {
            $design->unLike();
        } else {
            $design->like();
        }
        return $design->likes()->count();
    }

    public function isLikedByUser($id)
    {
        $design = $this->model->findOrFail($id);
        return $design->isLikedByUser(auth()->id());
    }

    public function search(\Illuminate\Http\Request $request)
    {
        // TODO: Implement search() method.
    }
}

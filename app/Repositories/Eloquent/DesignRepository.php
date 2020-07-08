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
        // TODO: Implement addComment() method.
    }

    public function like($id)
    {
        // TODO: Implement like() method.
    }

    public function isLikedByUser($id)
    {
        // TODO: Implement isLikedByUser() method.
    }

    public function search(\Illuminate\Http\Request $request)
    {
        // TODO: Implement search() method.
    }
}

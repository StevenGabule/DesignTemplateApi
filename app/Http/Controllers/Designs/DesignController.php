<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\ForUser;
use App\Repositories\Eloquent\Criteria\IsLive;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    protected $designs;

    public function __construct(IDesign $designs)
    {
        $this->designs = $designs;
    }

    public function index()
    {
        $designs = $this->designs->withCriteria([
            new LatestFirst(),
            new IsLive(),
            new ForUser(4),
            new EagerLoad(['user'])
        ])->all();
        return DesignResource::collection($designs);
    }

    public function update(Request $request, $id)
    {
        $design = $this->designs->find($id);

        $this->authorize('update', $design);

        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,' . $id],
            'description' => ['required', 'string', 'min:20', 'max:140'],
            'tags' => ['required'],
            'team' => ['required_if:assign_to_team,true']
        ]);

        $this->designs->update($id, [
            'team_id' => $request->team,
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => !$design->upload_successful ? false : $request->is_live,
        ]);

        // apply the tags
        $this->designs->applyTags($id, $request->tags);

        return new DesignResource($design);
    }

    public function user_owns_design($id)
    {
        $design = $this->designs->withCriteria([new ForUser(auth()->id())])->findWhereFirst('id', $id);
        return new DesignResource($design);
    }

    public function destroy($id)
    {
        $design = $this->designs->find($id);
        $this->authorize('delete', $design);

        foreach (['thumbnail', 'large', 'original'] as $size) {
            $d = $design->disk;
            $file_path = "uploads/designs/{$size}/{$design->image}";
            if (Storage::disk($d)->exists($file_path)) {
                Storage::disk($d)->delete($file_path);
            }
        }
        $this->designs->delete($id);
        return response()->json(['message' => 'Record Deleted.'], 200);
    }

    public function find_design($id)
    {
        $design = $this->designs->find($id);
        return new DesignResource($design);
    }

    public function find_by_slug($slug)
    {
        $design = $this->designs->withCriteria([new IsLive()])->findWhereFirst('slug', $slug);
        return new DesignResource($design);
    }
}

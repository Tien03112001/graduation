<?php

namespace App\Http\Controllers\modules\admin;

use App\Models\ContentTestimonial;
use App\Utils\FileUtil;
use App\Utils\HtmlUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentTestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', null);
        $query = (new ContentTestimonial())
            ->with('meta')
            ->orderBy('id', 'desc');
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $createdImages = [];
        $title = $request->input('title');
        $content = HtmlUtil::minify($request->input('content'), $createdImages);
        $slug = Str::slug($title);
        $model = new ContentTestimonial();
        $model->slug = $slug;
        $model->title = $title;
        $model->content = $content;
        $model->custom_name = $request->input('custom_name');
        $model->date = (new Carbon($request->input('date')))->toDateString();
        $model->nation = $request->input('nation');
        $model->order = 0;
        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            $model->image = $url;
            $model->alt = $request->input('alt', $model->name);
        }
        try {
            DB::beginTransaction();
            $model->save();
            DB::commit();
            $model->load(['meta']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $model = (new ContentTestimonial())->find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $title = $request->input('title');
        $slug = Str::slug($title);
        $createdImages = [];
        $content = HtmlUtil::minify($request->input('content'), $createdImages);
        $model->title = $title;
        $model->slug = $slug;
        $model->content = $content;
        $model->custom_name = $request->input('custom_name');
        $model->date = (new Carbon($request->input('date')))->toDateString();
        $model->nation = $request->input('nation');
        $model->order = $request->input('order');
        if ($request->hasFile('image')) {
            $url = Storage::disk('public')->put('images', $request->file('image'));
            if (empty($url)) {
                return $this->error('Not Found File');
            }
            FileUtil::removePublicFile($model->image);
            $model->image = $url;
            $model->alt = $request->input('alt', $model->name);
        }

        try {
            DB::beginTransaction();
            $model->save();
            DB::commit();
            $model->load(['meta']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = ContentTestimonial::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }

    public function change_order(Request $request, $id){
        $data = ContentTestimonial::find($id);
        $data->order = $request->order;
        $data->save();
        return $this->success($data);
    }
}

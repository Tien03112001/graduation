<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Models\ContentComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContentCommentController  extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $articleID = $request->input('content_article_id');
        $limit = $request->input('limit', null);
        $query = ContentComment::when($search, function ($q) use ($search) {
            return $q->where('author', 'like', '%' . $search . '%')
                ->orWhere('content', 'like', "%{$search}%");
        })
            ->where('content_article_id', $articleID)
            ->where('parent_id', 0)
            ->with('children');
        if ($limit) {
            $data = $query->paginate($limit);
        } else {
            $data = $query->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $data = new ContentComment();
        $data->content_article_id = $request->input('content_article_id');
        $data->parent_id = $request->input('parent_id');
        $data->content = $request->input('content');
        $data->author = $request->input('author');
        $data->published = $request->input('published');
        $data->save();
        return $this->success($data);
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = ContentComment::find($id);
            $data->content_article_id = $request->input('content_article_id');
            $data->parent_id = $request->input('parent_id');
            $data->content = $request->input('content');
            $data->author = $request->input('author');
            $data->published = $request->input('published');
            $data->save();
            DB::commit();
            return $this->success($data);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = ContentComment::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }

    public function enable($id)
    {
        $model = ContentComment::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->published = true;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function disable($id)
    {
        $model = ContentComment::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        try {
            DB::beginTransaction();
            $model->published = false;
            $model->save();
            DB::commit();
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}

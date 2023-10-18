<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $article_id = $request->input('article_id');
        if ($article_id) {
            $search = $request->input('search', null);
            $limit = $request->input('limit', null);
            $query = (new Comment())
                ->when($search, function ($q) use ($search) {
                    return $q->where('name', 'like', '%' . $search . '%');
                })
                ->where('article_id', $article_id)
                ->orderBy('id', 'desc');
            if ($limit) {
                $data = $query->paginate($limit);
            } else {
                $data = $query->get();
            }
            return $this->success($data);
        } else {
            return $this->success([]);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $model = (new Comment());
            $model->author = $request->input('author');
            $model->content = $request->input('content');
            $model->reserve_at = $request->input('reserve_at');
            if (isset($model->reserve_at)) {
                $model->published = 0;
            } else {
                $model->published = $request->input('published', 0);
            }
            $model->parent_id = $request->input('parent_id');
            $model->article_id = $request->input('article_id');
            $model->save();
            DB::commit();
            $model->load(['parent']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $model = (new Comment())->find($id);
            $model->author = $request->input('author');
            $model->content = $request->input('content');
            $model->reserve_at = $request->input('reserve_at');
            if (isset($model->reserve_at)) {
                $model->published = 0;
            } else {
                $model->published = $request->input('published', $model->published);
            }

            $model->save();
            DB::commit();
            $model->load(['parent']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $model = Comment::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }
}

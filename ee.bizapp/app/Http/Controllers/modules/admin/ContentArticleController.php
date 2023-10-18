<?php

namespace App\Http\Controllers\modules\admin;

use App\Models\ContentArticle;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContentArticleController extends Controller
{
    public function update(Request $request, $id){
        try {
            DB::beginTransaction();
            $content = $request->input('content');
            if ($content){
                $createdImages = [];
                $content = HtmlUtil::minify($request->input('content'), $createdImages);
            }
            $model = (new ContentArticle())->find($id);
            $model->title = $request->input('title', $model->title);
            $model->content = $content;
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

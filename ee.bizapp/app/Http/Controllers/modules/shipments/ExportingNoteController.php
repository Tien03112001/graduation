<?php

namespace App\Http\Controllers\modules\shipments;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ExportingNote;
use App\Utils\CacheUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportingNoteController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $search = $request->input('search');
        $sql = (new ExportingNote())
            ->when(isset($search), function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'DESC');
        if ($limit) {
            $data = $sql->paginate($limit);
        } else {
            $data = $sql->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        // TODO: Implement store() method.
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function printBill($id)
    {
        $model = ExportingNote::with('details')->find($id);
        if (empty($model)) {
            return $this->error('Đối tượng không tồn tại');
        }
        $settings = CacheUtil::getAppSetting();
        try {
            $src = view('exporting.default', compact('model', 'settings'))->render();
            $file_name = sprintf('print_html/%d.html', now()->getTimestamp());
            file_put_contents(storage_path('app/public/' . $file_name), $src);
            $link = Storage::disk('public')->url($file_name);
            return $this->success(compact('link'));
        } catch (\Throwable $e) {
            return $this->error('Không thể sinh file');
        }
    }
}

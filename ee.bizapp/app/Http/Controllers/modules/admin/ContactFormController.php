<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactFormController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $sql = (new ContactForm())
            ->when($search, function ($q) use ($search) {
                return $q->where('name', 'like', '%' . $search . '%')
                    ->where('description', 'like', '%' . $search . '%');
            })
            ->withCount('pending_contacts')
            ->orderBy('id', 'ASC');
        if ($limit) {
            $data = $sql->paginate($limit);
        } else {
            $data = $sql->get();
        }
        return $this->success($data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $model = new ContactForm();
            $model->name = $request->input('name');
            $model->description = $request->input('description');
            $model->save();
            DB::commit();
            $model->loadCount(['pending_contacts']);
            return $this->success($model);
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        $model = ContactForm::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->delete();
        return $this->success($model);
    }


}

<?php

namespace App\Http\Controllers\modules\admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\ContactFormData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ContactFormDataController extends Controller implements ApiController
{
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $limit = $request->input('limit', null);
        $contactFormId = $request->input('contact_form_id');
        $sql = (new ContactFormData())
            ->when(isset($search), function ($q) use ($search) {
                return $q->whereHas('values', function (Builder $q1) use ($search) {
                    $q1->where('name', 'like', '%' . $search . '%')
                        ->orWhere('value', 'like', '%' . $search . '%');
                });
            })
            ->where('form_id', $contactFormId)
            ->where('status', false)
            ->with('values')
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
        $model = ContactFormData::find($id);
        if (empty($model)) {
            return $this->error('Not found');
        }
        $model->status = true;
        $model->save();
        return $this->success($model);
    }


}

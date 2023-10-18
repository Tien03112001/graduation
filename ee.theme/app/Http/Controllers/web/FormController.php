<?php

namespace App\Http\Controllers\web;

use App\Common\SEO\SEOPage;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Models\Form;
use App\Models\FormAttribute;
use App\Models\MetaData;
use App\Repository\FormDataRepositoryInterface;
use App\Repository\FormRepositoryInterface;
use App\Repository\FormValueRepositoryInterface;
use App\Utils\HtmlUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FormController extends RestController
{
    protected $formDataRepository;
    protected $formValueRepository;

    public function __construct(FormRepositoryInterface $repository, FormDataRepositoryInterface $formDataRepository,
                                FormValueRepositoryInterface $formValueRepository)
    {
        parent::__construct($repository);
        $this->formDataRepository = $formDataRepository;
        $this->formValueRepository = $formValueRepository;
    }

    public function store(Request $request)
    {

        $validator = $this->validateRequest($request, [
            'name' => 'required'
        ]);
        if ($validator) {
            abort(400);
        }
        $form = $this->repository->find([
            WhereClause::query('name', $request->name)
        ], null, ['properties']);

        if (empty($form)) {
            abort(404);
        }

        if (!($form instanceof Form)) {
            abort(404);
        }

        $formRules = [];

        foreach ($form->properties as $a) {
            if ($a instanceof FormAttribute) {
                $formRules[$a->name] = 'required';
            }
        }

        $validator = $this->validateRequest($request, $formRules);
        if ($validator) {
            abort(400);
        }

        try {
            DB::beginTransaction();
            if ($a instanceof FormAttribute) {
                $data = $this->formDataRepository->create([
                    'form_id' => $form->id,
                    'attribute_id' => '1',
                    'form_name' => $form->name
                ]);
                foreach ($form->properties as $a) {
                    if ($a instanceof FormAttribute) {
                        $this->formValueRepository->create([
                            'form_id' => $form->id,
                            'attribute_id' => $a->id,
                            'form_name' => $form->name,
                            'data_id' => $data->id,
                            'value' => $request->input($a->name)
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            abort(500);
        }

        //tracking form submit

        $page = new SEOPage('Gửi thông tin', new MetaData(['robots' => 'noindex']));
        return HtmlUtil::optimize(view('theme.form.success', compact('page')));
    }

}

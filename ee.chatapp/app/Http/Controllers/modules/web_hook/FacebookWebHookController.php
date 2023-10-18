<?php

namespace App\Http\Controllers\modules\web_hook;

use App\Http\Controllers\RestController;
use App\Jobs\SaveFacebookPageEventJob;
use App\Repository\FacebookWebhookRequestRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Illuminate\Http\Request;

class FacebookWebHookController extends RestController
{
    protected $webhookRequestRepository;

    public function __construct(UserRepositoryInterface $repository, FacebookWebhookRequestRepositoryInterface $webhookRequestRepository)
    {
        parent::__construct($repository);
        $this->webhookRequestRepository = $webhookRequestRepository;
    }

    public function verify(Request $request)
    {
        $validator = $this->validateRequest($request, [
            'hub_mode' => 'required',
            'hub_challenge' => 'required',
            'hub_verify_token' => 'required'
        ]);
        if ($validator) {
            return response('', 403);
        }
        $token = $request->input('hub_verify_token');

        if ($token != 'anthinhphatjsc') {
            return response('', 403);
        }
        $challenge = $request->input('hub_challenge');
        return $challenge;
    }

    public function subscribe(Request $request)
    {
        $object = $request->input('object');
        if ($object == 'page') {
            SaveFacebookPageEventJob::dispatch($request->input('entry'));
            return response('EVENT_RECEIVED', 200);
        }
        return response('NO_EVENT_RECEIVED', 200);
    }

}

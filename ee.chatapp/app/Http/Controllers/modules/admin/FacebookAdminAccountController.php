<?php

namespace App\Http\Controllers\modules\admin;

use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Repository\FacebookAdminAccountRepositoryInterface;
use App\Repository\FacebookFanpageRepositoryInterface;
use App\Utils\FacebookApi;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FacebookAdminAccountController extends RestController
{
    protected $fanpageRepository;

    public function __construct(FacebookAdminAccountRepositoryInterface $repository, FacebookFanpageRepositoryInterface $fanpageRepository)
    {
        parent::__construct($repository);
        $this->fanpageRepository = $fanpageRepository;
    }

    public function store(Request $request)
    {
         $validator = $this->validateRequest($request, [
             'data' => 'required',
         ]);
         if ($validator) {
             return $this->errorClient($validator);
         }
         $data = $request->input('data');

        $data = json_decode($data, true);
        Log::info(print_r($data, true));

        if (empty($data['status']) || $data['status'] != 'connected' || empty($data['authResponse'])) {
            return $this->errorClient('Đăng nhập facebook không thành công');
        }

        $authResponse = $data['authResponse'];

        $user = $this->repository->find([
            WhereClause::query('id', $authResponse['userID']),
        ]);

        if (empty($user)) {
            try {
                $longToken = FacebookApi::getInstance()
                    ->setAccessToken($authResponse['accessToken'])
                    ->getLongAccessToken();
                $user = $this->repository->create([
                    'id' => $authResponse['userID'],
                    'name' => $authResponse['userID'],
                    'short_access_token' => $authResponse['accessToken'],
                    'short_expired_at' => Carbon::createFromTimestamp($authResponse['data_access_expiration_time'])->toDateTimeString(),
                    'long_access_token' => $longToken->getValue(),
                    'long_expired_at' => Carbon::parse($longToken->getExpiresAt())->toDateTimeString()
                ]);
            } catch (FacebookSDKException $e) {
                Log::error($e);
                return $this->errorClient('Không thể kết nối đến Facebook');
            }
        } else {
            $longTokenExpiredAt = Carbon::parse($user->long_expired_at);
            if (now()->isAfter($longTokenExpiredAt)) {
                try {
                    $longToken = FacebookApi::getInstance()
                        ->setAccessToken($authResponse['accessToken'])
                        ->getLongAccessToken();
                    $user = $this->repository->update($user, [
                        'long_access_token' => $longToken->getValue(),
                        'long_expired_at' => Carbon::parse($longToken->getExpiresAt())->toDateTimeString()
                    ]);
                } catch (FacebookSDKException $e) {
                    Log::error($e);
                    return $this->errorClient('Không thể kết nối đến Facebook');
                }
            }
        }

        try {
            $pages = FacebookApi::getInstance()->setAccessToken($user->long_access_token)->getAllPages($user->id);
            foreach ($pages as $p) {
                $page = $this->fanpageRepository->findById($p['id']);
                if (empty($page)) {
                    $page = $this->fanpageRepository->create([
                        'id' => $p['id'],
                        'name' => $p['name'],
                        'access_token' => $p['access_token']
                    ]);
                } else{
                    $page = $this->fanpageRepository->update($p['id'],[
                        'name' => $p['name'],
                        'access_token' => $p['access_token']
                    ]);
                }
                FacebookApi::getInstance()
                    ->setAccessToken($p['access_token'])
                    ->subscribeApps($page);
            }
        } catch (FacebookSDKException $e) {
            Log::error($e);
            return $this->errorClient('Không thể kết nối đến Facebook');
        }

        return $this->success($user);
    }

}

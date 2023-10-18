<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 12/11/2022
 * Time: 17:22
 */

namespace App\Utils;


use App\Common\Entities\FacebookMessageSentEntity;
use App\Common\SingletonPattern;
use App\Models\FacebookFanpage;
use App\Models\FacebookFileAttachment;
use App\Models\Message;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\FacebookResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FacebookApi extends SingletonPattern
{
    /**
     * @return FacebookApi
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /* @var Facebook */
    protected $fbClient;

    /**
     * FacebookApi constructor.
     */
    protected function __construct()
    {
        try {
            $this->fbClient = new Facebook([
                'app_id' => config('facebook_api.app.id'),
                'app_secret' => config('facebook_api.app.secret'),
                'default_graph_version' => 'v15.0',
            ]);
        } catch (FacebookSDKException $e) {
            Log::error($e);
        }
    }

    /**
     * @param \Facebook\Authentication\AccessToken|string $accessToken
     * @return FacebookApi
     */
    public function setAccessToken($accessToken)
    {
        $this->fbClient->setDefaultAccessToken($accessToken);
        return $this;
    }

    /**
     * @return  \Facebook\Authentication\AccessToken|null
     */
    public function getAccessToken()
    {
        return $this->fbClient ? $this->fbClient->getDefaultAccessToken() : null;
    }

    /**
     * @return Facebook
     */
    public function getFbClient(): Facebook
    {
        return $this->fbClient;
    }


    public function getAllPaginations(FacebookResponse $response)
    {
        try {
            $data = array();
            $edge = $response->getGraphEdge();
            while ($edge) {
                $data = array_merge($data, $edge->asArray());
                $edge = $this->fbClient->next($edge);
            }
            return $data;
        } catch (FacebookSDKException $e) {
            Log::error($e);
        }
        return null;
    }


    /**
     * @return bool|\Facebook\Authentication\AccessToken
     * @throws FacebookSDKException
     */
    public function getLongAccessToken()
    {
        if (isset($this->fbClient)) {
            $oAuth2Client = $this->fbClient->getOAuth2Client();
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($this->getAccessToken());
            $this->setAccessToken($longLivedAccessToken);
            return $longLivedAccessToken;
        }
        return false;
    }

    /**
     * @param $userID
     * @return mixed
     * @throws FacebookSDKException
     */
    public function getAllPages($userID)
    {
        if (empty($this->fbClient)) {
            return false;
        }
        $response = $this->fbClient->get(
            "$userID/accounts"
        );

        if ($response->getHttpStatusCode() == 200) {
            return $this->getAllPaginations($response);
        }
        return false;
    }

    /**
     * @param $userID
     * @return mixed
     * @throws FacebookSDKException
     */
    public function getAllAdAccounts($userID)
    {
        if (empty($this->fbClient)) {
            return false;
        }
        $response = $this->fbClient->get(
            "$userID/adaccounts"
        );

        if ($response->getHttpStatusCode() == 200) {
            return $this->getAllPaginations($response);
        }
        return false;
    }


    /**
     * @param FacebookFanpage $page
     * @param $userID
     * @return array|boolean
     * @throws FacebookSDKException
     */
    public function getUserProfile(FacebookFanpage $page, $userID)
    {
        if (empty($this->fbClient) || empty($userID)) {
            return false;
        }
        $this->setAccessToken($page->access_token);
        $response = $this->fbClient->get(
            "$userID?" . http_build_query([
                'fields' => 'first_name, last_name, gender, locale, timezone'
            ])
        );
        if ($response->getHttpStatusCode() == 200) {
            return $response->getDecodedBody();
        }
        return false;

    }

    /**
     * @param FacebookFanpage $page
     * @return mixed
     * @throws FacebookSDKException
     */
    public function getSubscribedApps(FacebookFanpage $page)
    {
        if (empty($this->fbClient)) {
            return false;
        }
        $this->setAccessToken($page->access_token);
        $response = $this->fbClient->get(
            "$page->id/subscribed_apps"
        );

        if ($response->getHttpStatusCode() == 200) {
            return $this->getAllPaginations($response);
        }
        return false;
    }

    /**
     * @param FacebookFanpage $page
     * @return mixed
     * @throws FacebookSDKException
     */
    public function subscribeApps(FacebookFanpage $page)
    {
        if (empty($this->fbClient)) {
            return false;
        }
        $this->setAccessToken($page->access_token);
        $response = $this->fbClient->post(
            "$page->id/subscribed_apps",
            [
                'subscribed_fields' => [
                    'feed',
                    'messages',
                    'message_deliveries',
                    'message_reads',
                    'messaging_customer_information'
                ]
            ]
        );

        if ($response->getHttpStatusCode() == 200) {
            return $response;
        }
        return false;
    }


    /**
     * @param FacebookFanpage $page
     * @param $recipientId
     * @param Message $message
     * @return bool|FacebookMessageSentEntity
     * @throws FacebookSDKException
     */
    public function sendMessage(FacebookFanpage $page, $recipientId, Message $message)
    {
        if (empty($this->fbClient)) {
            return false;
        }

        $this->setAccessToken($page->access_token);

        if (isset($message->content)) {
            $content = $message->content;
            if (Str::startsWith($content, 'attachment_id:')) {
                $attachment = (new FacebookFileAttachment())
                    ->newQuery()
                    ->find(Str::replace('attachment_id:', '', $content));
                if (empty($attachment)) {
                    $response = $this->fbClient->post(
                        "$page->id/messages",
                        [
                            'recipient' => ['id' => $recipientId],
                            'messaging_type' => 'RESPONSE',
                            'message' => [
                                'text' => $message->content,
                            ],
                        ]
                    );
                } else {
                    $response = $this->fbClient->post(
                        "$page->id/messages",
                        [
                            'recipient' => ['id' => $recipientId],
                            'messaging_type' => 'RESPONSE',
                            'message' => [
                                'attachment' => [
                                    'type' => $attachment->type,
                                    'payload' => [
                                        'attachment_id' => $attachment->id
                                    ]
                                ],
                            ],
                        ]
                    );
                }
            } else {
                $response = $this->fbClient->post(
                    "$page->id/messages",
                    [
                        'recipient' => ['id' => $recipientId],
                        'messaging_type' => 'RESPONSE',
                        'message' => [
                            'text' => $message->content,
                        ],
                    ]
                );
            }
            if ($response->getHttpStatusCode() != 200) {
                return false;
            }

            return new FacebookMessageSentEntity($response->getDecodedBody());
        }

        return false;

    }


    /**
     * @param FacebookFanpage $page
     * @param FacebookFileAttachment $attachment
     * @return bool|FacebookResponse
     * @throws FacebookSDKException
     */
    public function uploadFileAttachment(FacebookFanpage $page, FacebookFileAttachment $attachment)
    {
        if (empty($this->fbClient)) {
            return false;
        }
        $this->setAccessToken($page->access_token);
        $response = $this->fbClient->post(
            "me/message_attachments",
            [
                'message' => [
                    'attachment' => [
                        'type' => $attachment->type,
                        'payload' => [
                            'is_reusable' => true,
                            'url' => $attachment->url
                        ]
                    ],
                ],
            ]
        );

        if ($response->getHttpStatusCode() == 200) {
            $data = $response->getDecodedBody();
            return $data['attachment_id'];
        }
        return false;
    }

}
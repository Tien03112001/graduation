<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 3/4/2023
 * Time: 16:45
 */

namespace App\Common\FacebookConversion;


use App\Models\Order;
use App\Repository\ProvinceRepositoryInterface;
use App\Utils\Caches\AppSettingUtil;
use App\Utils\CartUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class FacebookConversionUserDataEvent
{
    protected $em;
    protected $ph;
    protected $fn;
    protected $ln;
    protected $ge;
    protected $db;
    protected $ct;
    protected $st;
    protected $zp;
    protected $country;
    protected $external_id;
    protected $client_ip_address;
    protected $client_user_agent;
    protected $fbc;
    protected $fbp;
    protected $subscription_id;
    protected $fb_login_id;
    protected $lead_id;

    public function __construct(Request $request)
    {
        $this->fbc = $request->cookie('_fbc');
        $this->fbp = $request->cookie('_fbp');
        $this->client_ip_address = $request->ip();
        $this->client_user_agent = $request->userAgent();
        $this->external_id = $request->cookie('ecuuid', null);
        $this->country = hash('sha256', $request->input('country', AppSettingUtil::getInstance()->getCachedValue('country', 'vn')));
    }

    public function parseOrder(Order $order)
    {
        $this->em = hash('sha256', $order->customer_email);
        $this->ph = hash('sha256', $order->customer_phone);
        if (isset($order->province)) {
            $this->ct = hash('sha256', $order->province->name);
        }
    }

    /**
     * @return mixed
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param mixed $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function getPh()
    {
        return $this->ph;
    }

    /**
     * @param mixed $ph
     */
    public function setPh($ph)
    {
        $this->ph = $ph;
    }

    /**
     * @return mixed
     */
    public function getFn()
    {
        return $this->fn;
    }

    /**
     * @param mixed $fn
     */
    public function setFn($fn)
    {
        $this->fn = $fn;
    }

    /**
     * @return mixed
     */
    public function getLn()
    {
        return $this->ln;
    }

    /**
     * @param mixed $ln
     */
    public function setLn($ln)
    {
        $this->ln = $ln;
    }

    /**
     * @return mixed
     */
    public function getGe()
    {
        return $this->ge;
    }

    /**
     * @param mixed $ge
     */
    public function setGe($ge)
    {
        $this->ge = $ge;
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getCt()
    {
        return $this->ct;
    }

    /**
     * @param mixed $ct
     */
    public function setCt($ct)
    {
        $this->ct = $ct;
    }

    /**
     * @return mixed
     */
    public function getSt()
    {
        return $this->st;
    }

    /**
     * @param mixed $st
     */
    public function setSt($st)
    {
        $this->st = $st;
    }

    /**
     * @return mixed
     */
    public function getZp()
    {
        return $this->zp;
    }

    /**
     * @param mixed $zp
     */
    public function setZp($zp)
    {
        $this->zp = $zp;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getExternalId()
    {
        return $this->external_id;
    }

    /**
     * @param mixed $external_id
     */
    public function setExternalId($external_id)
    {
        $this->external_id = $external_id;
    }

    /**
     * @return mixed
     */
    public function getClientIpAddress()
    {
        return $this->client_ip_address;
    }

    /**
     * @param mixed $client_ip_address
     */
    public function setClientIpAddress($client_ip_address)
    {
        $this->client_ip_address = $client_ip_address;
    }

    /**
     * @return mixed
     */
    public function getClientUserAgent()
    {
        return $this->client_user_agent;
    }

    /**
     * @param mixed $client_user_agent
     */
    public function setClientUserAgent($client_user_agent)
    {
        $this->client_user_agent = $client_user_agent;
    }

    /**
     * @return mixed
     */
    public function getFbc()
    {
        return $this->fbc;
    }

    /**
     * @param mixed $fbc
     */
    public function setFbc($fbc)
    {
        $this->fbc = $fbc;
    }

    /**
     * @return mixed
     */
    public function getFbp()
    {
        return $this->fbp;
    }

    /**
     * @param mixed $fbp
     */
    public function setFbp($fbp)
    {
        $this->fbp = $fbp;
    }

    /**
     * @return mixed
     */
    public function getSubscriptionId()
    {
        return $this->subscription_id;
    }

    /**
     * @param mixed $subscription_id
     */
    public function setSubscriptionId($subscription_id)
    {
        $this->subscription_id = $subscription_id;
    }

    /**
     * @return mixed
     */
    public function getFbLoginId()
    {
        return $this->fb_login_id;
    }

    /**
     * @param mixed $fb_login_id
     */
    public function setFbLoginId($fb_login_id)
    {
        $this->fb_login_id = $fb_login_id;
    }

    /**
     * @return mixed
     */
    public function getLeadId()
    {
        return $this->lead_id;
    }

    /**
     * @param mixed $lead_id
     */
    public function setLeadId($lead_id)
    {
        $this->lead_id = $lead_id;
    }


    public function toString()
    {
        return json_decode(json_encode(get_object_vars($this)), true);
    }

    public function toArray(): array
    {
        $arr = [];
        try {
            $reflection = new \ReflectionClass($this);
            $properties = $reflection->getProperties();

            foreach ($properties as $p) {
                $arr[$p->name] = $this->{$p->name};
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
        return $arr;
    }

}

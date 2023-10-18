<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 3/4/2023
 * Time: 16:43
 */

namespace App\Common\FacebookConversion;


use App\Models\Order;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Utils\CartUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FacebookConversionEvent
{

    protected $event_name;
    protected $event_time;
    /* @var FacebookConversionUserDataEvent */
    protected $user_data;
    /* @var FacebookConversionCustomDataEvent */
    protected $custom_data;
    protected $event_source_url;
    protected $opt_out;
    protected $event_id;
    protected $action_source;
    protected $data_processing_options;
    protected $data_processing_options_country;
    protected $data_processing_options_state;

    protected $session_id;
    protected $created_time;

    public function __construct($eventName, Request $request)
    {
        $this->event_name = $eventName;
        $this->opt_out = true;
        $this->action_source = 'website';
        $this->event_time = now()->timezone('UTC')->timestamp;
        $this->user_data = new FacebookConversionUserDataEvent($request);
        $this->custom_data = new FacebookConversionCustomDataEvent();
        $this->event_source_url = $request->url();
        $this->session_id = $request->getSession()->getId();
        $this->created_time = now()->toDateTimeString();
    }

    /**
     * @param Page $page
     * @param Request $request
     * @return FacebookConversionEvent
     */
    public static function createPageViewEvent(Page $page, Request $request): FacebookConversionEvent
    {
        $event = new FacebookConversionEvent('PageView', $request);
        $event->setCustomData(FacebookConversionCustomDataEvent::viewPage($page));
        return $event;
    }

    /**
     * @param $name
     * @param $url
     * @param Request $request
     * @return FacebookConversionEvent
     */
    public static function createCustomPageViewEvent($name, Request $request): FacebookConversionEvent
    {
        $event = new FacebookConversionEvent('PageView', $request);
        $event->getCustomData()->setContentName($name);
        return $event;
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return FacebookConversionEvent
     */
    public static function createPostViewEvent(Post $post, Request $request): FacebookConversionEvent
    {
        $event = new FacebookConversionEvent('PageView', $request);
        $event->setCustomData(FacebookConversionCustomDataEvent::viewPost($post));
        return $event;
    }

    /**
     * @param PostCategory $postCategory
     * @param Request $request
     * @return FacebookConversionEvent
     */
    public static function createPostCategoryViewEvent(PostCategory $postCategory, Request $request): FacebookConversionEvent
    {
        $event = new FacebookConversionEvent('PageView', $request);
        $event->setCustomData(FacebookConversionCustomDataEvent::viewPostCategory($postCategory));
        return $event;
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return FacebookConversionEvent
     */
    public static function createProductViewEvent(Product $product, Request $request): FacebookConversionEvent
    {
        $event = new FacebookConversionEvent('ViewContent', $request);
        $event->setCustomData(FacebookConversionCustomDataEvent::viewProduct($product));
        return $event;
    }

    /**
     * @param ProductCategory $category
     * @param array $products
     * @param Request $request
     * @return FacebookConversionEvent
     */
    public static function createProductCategoryEvent(ProductCategory $category, array $products, Request $request): FacebookConversionEvent
    {
        $event = new FacebookConversionEvent('ViewContent', $request);
        $event->setCustomData(FacebookConversionCustomDataEvent::viewProductCategory($category, $products));
        return $event;
    }

    public static function createAddToCartEvent(Product $product, Request $request): FacebookConversionEvent
    {
        $event = new FacebookConversionEvent('ViewContent', $request);
        $event->setCustomData(FacebookConversionCustomDataEvent::addToCart($product, $request->input('quantity', 1)));
        return $event;
    }

    public static function createInitiateCheckoutEvent(CartUtil $cart, Request $request): FacebookConversionEvent
    {
        $event = new FacebookConversionEvent('InitiateCheckout', $request);
        $event->setCustomData(FacebookConversionCustomDataEvent::initiateCheckout($cart));
        return $event;
    }

    public static function createPurchaseEvent(Order $order, Request $request): FacebookConversionEvent
    {
        $event = new FacebookConversionEvent('Purchase', $request);
        $event->setCustomData(FacebookConversionCustomDataEvent::purchase($order));
        $event->getUserData()->parseOrder($order);
        return $event;
    }

    /**
     * @return mixed
     */
    public function getEventName()
    {
        return $this->event_name;
    }

    /**
     * @param mixed $event_name
     */
    public function setEventName($event_name)
    {
        $this->event_name = $event_name;
    }

    /**
     * @return float
     */
    public function getEventTime(): float
    {
        return $this->event_time;
    }

    /**
     * @param float $event_time
     */
    public function setEventTime(float $event_time)
    {
        $this->event_time = $event_time;
    }

    /**
     * @return FacebookConversionUserDataEvent
     */
    public function getUserData(): FacebookConversionUserDataEvent
    {
        return $this->user_data;
    }

    /**
     * @param FacebookConversionUserDataEvent $user_data
     */
    public function setUserData(FacebookConversionUserDataEvent $user_data)
    {
        $this->user_data = $user_data;
    }

    /**
     * @return FacebookConversionCustomDataEvent
     */
    public function getCustomData(): FacebookConversionCustomDataEvent
    {
        return $this->custom_data;
    }

    /**
     * @param FacebookConversionCustomDataEvent $custom_data
     */
    public function setCustomData(FacebookConversionCustomDataEvent $custom_data)
    {
        $this->custom_data = $custom_data;
    }

    /**
     * @return mixed
     */
    public function getEventSourceUrl()
    {
        return $this->event_source_url;
    }

    /**
     * @param mixed $event_source_url
     */
    public function setEventSourceUrl($event_source_url)
    {
        $this->event_source_url = $event_source_url;
    }

    /**
     * @return mixed
     */
    public function getOptOut()
    {
        return $this->opt_out;
    }

    /**
     * @param mixed $opt_out
     */
    public function setOptOut($opt_out)
    {
        $this->opt_out = $opt_out;
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * @param mixed $event_id
     */
    public function setEventId($event_id)
    {
        $this->event_id = $event_id;
    }

    /**
     * @return mixed
     */
    public function getActionSource()
    {
        return $this->action_source;
    }

    /**
     * @param mixed $action_source
     */
    public function setActionSource($action_source)
    {
        $this->action_source = $action_source;
    }

    /**
     * @return mixed
     */
    public function getDataProcessingOptions()
    {
        return $this->data_processing_options;
    }

    /**
     * @param mixed $data_processing_options
     */
    public function setDataProcessingOptions($data_processing_options)
    {
        $this->data_processing_options = $data_processing_options;
    }

    /**
     * @return mixed
     */
    public function getDataProcessingOptionsCountry()
    {
        return $this->data_processing_options_country;
    }

    /**
     * @param mixed $data_processing_options_country
     */
    public function setDataProcessingOptionsCountry($data_processing_options_country)
    {
        $this->data_processing_options_country = $data_processing_options_country;
    }

    /**
     * @return mixed
     */
    public function getDataProcessingOptionsState()
    {
        return $this->data_processing_options_state;
    }

    /**
     * @param mixed $data_processing_options_state
     */
    public function setDataProcessingOptionsState($data_processing_options_state)
    {
        $this->data_processing_options_state = $data_processing_options_state;
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
                if ($p->name == 'user_data' || $p->name == 'custom_data') {
                    $arr[$p->name] = $this->{$p->name}->toArray();
                } else {
                    $arr[$p->name] = $this->{$p->name};
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
        return $arr;
    }
}

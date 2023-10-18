<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 3/4/2023
 * Time: 16:45
 */

namespace App\Common\FacebookConversion;


use App\Models\Order;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Utils\Caches\AppSettingUtil;
use App\Utils\CartUtil;
use Illuminate\Support\Facades\Log;

class FacebookConversionCustomDataEvent
{
    protected $value;
    protected $currency;
    protected $content_name;
    protected $content_category;
    protected $content_ids;
    protected $contents;
    protected $content_type;
    protected $order_id;
    protected $predicted_ltv;
    protected $num_items;
    protected $search_string;
    protected $status;
    protected $delivery_category;

    public function __construct()
    {
    }

    public static function viewPage(Page $page): FacebookConversionCustomDataEvent
    {
        $event = new FacebookConversionCustomDataEvent();
        $event->setContentName($page->name);
        $event->setContentType('page');
        $event->setContents([]);
        return $event;
    }


    public static function viewPost(Post $post): FacebookConversionCustomDataEvent
    {
        $event = new FacebookConversionCustomDataEvent();
        $event->setContentName($post->name);
        $event->setContentType('post');
        $event->setContentCategory($post->category_slug);
        $event->setContents([]);
        return $event;
    }

    public static function viewPostCategory(PostCategory $postCategory): FacebookConversionCustomDataEvent
    {
        $event = new FacebookConversionCustomDataEvent();
        $event->setContentName($postCategory->name);
        $event->setContentType('post_group');
        $event->setContents([]);
        return $event;
    }

    public static function viewProductCategory(ProductCategory $category, array $products): FacebookConversionCustomDataEvent
    {
        $event = new FacebookConversionCustomDataEvent();
        $event->setContentName($category->name);
        $event->setContentType('product_group');
        $contents = [];
        $content_ids = [];
        foreach ($products as $product) {
            if ($product instanceof Product) {
                array_push($contents, [
                    'id' => $product->code,
                    'item_price' => $product->sale_price ?? $product->price
                ]);
                array_push($content_ids, $product->code);
            }
        }
        $event->setContents($contents);
        $event->setContentIds($content_ids);
        return $event;
    }

    public static function viewProduct(Product $product): FacebookConversionCustomDataEvent
    {
        $event = new FacebookConversionCustomDataEvent();
        $event->setContentName($product->name);
        $event->setContentType('product');
        $contents = [];
        $content_ids = [];
        array_push($contents, [
            'id' => $product->code,
            'item_price' => $product->sale_price ?? $product->price
        ]);
        array_push($content_ids, $product->code);

        $event->setContentIds($content_ids);
        $event->setContents($contents);
        return $event;
    }

    public static function addToCart(Product $product, int $quantity = 1): FacebookConversionCustomDataEvent
    {
        $event = new FacebookConversionCustomDataEvent();
        $event->setContentName('addToCart');
        $event->setContentType('addToCart');
        $event->setContentCategory('addToCart');
        $contents = [];
        array_push($contents, [
            'id' => $product->code,
            'item_price' => $product->sale_price ?? $product->price,
            'quantity' => $quantity
        ]);
        $event->setContents($contents);
        return $event;
    }

    public static function initiateCheckout(CartUtil $cart): FacebookConversionCustomDataEvent
    {
        $event = new FacebookConversionCustomDataEvent();
        $event->setContentName('initiateCheckout');
        $event->setContentType('initiateCheckout');
        $event->setContentCategory('initiateCheckout');
        $event->setNumItems(strval($cart->getTotalQuantity()));
        $contents = [];
        $event->setContents($contents);
        foreach ($cart->getItems() as $key => $item) {
            array_push($contents, [
                'id' => $item['item']['code'],
                'item_price' => $item['unit_price'],
                'quantity' => $item['quantity']
            ]);
        }
        return $event;
    }

    public static function purchase(Order $order): FacebookConversionCustomDataEvent
    {
        $event = new FacebookConversionCustomDataEvent();
        $event->setContentCategory('purchase');
        $event->setContentName('purchase');
        $event->setContentType('product_group');
        $contents = [];
        foreach ($order->items as $item) {
            array_push($contents, [
                'id' => $item->product->code,
                'item_price' => $item->price
            ]);
        }
        $event->setContents($contents);
        $event->setOrderId($order->code);
        $event->setDeliveryCategory('home_delivery');
        $event->setValue($order->amount);
        $event->setCurrency(AppSettingUtil::getInstance()->getCachedValue('default_currency', 'VND'));
        return $event;
    }


    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getContentName()
    {
        return $this->content_name;
    }

    /**
     * @param mixed $content_name
     */
    public function setContentName($content_name)
    {
        $this->content_name = $content_name;
    }

    /**
     * @return mixed
     */
    public function getContentCategory()
    {
        return $this->content_category;
    }

    /**
     * @param mixed $content_category
     */
    public function setContentCategory($content_category)
    {
        $this->content_category = $content_category;
    }

    /**
     * @return mixed
     */
    public function getContentIds()
    {
        return $this->content_ids;
    }

    /**
     * @param mixed $content_ids
     */
    public function setContentIds($content_ids)
    {
        $this->content_ids = $content_ids;
    }

    /**
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param mixed $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return mixed
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * @param mixed $content_type
     */
    public function setContentType($content_type)
    {
        $this->content_type = $content_type;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param mixed $order_id
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * @return mixed
     */
    public function getPredictedLtv()
    {
        return $this->predicted_ltv;
    }

    /**
     * @param mixed $predicted_ltv
     */
    public function setPredictedLtv($predicted_ltv)
    {
        $this->predicted_ltv = $predicted_ltv;
    }

    /**
     * @return mixed
     */
    public function getNumItems()
    {
        return $this->num_items;
    }

    /**
     * @param mixed $num_items
     */
    public function setNumItems($num_items)
    {
        $this->num_items = $num_items;
    }

    /**
     * @return mixed
     */
    public function getSearchString()
    {
        return $this->search_string;
    }

    /**
     * @param mixed $search_string
     */
    public function setSearchString($search_string)
    {
        $this->search_string = $search_string;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getDeliveryCategory()
    {
        return $this->delivery_category;
    }

    /**
     * @param mixed $delivery_category
     */
    public function setDeliveryCategory($delivery_category)
    {
        $this->delivery_category = $delivery_category;
    }

    public function toString()
    {
        return json_decode(json_encode(get_object_vars($this)), true);
    }

    public function toArray()
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

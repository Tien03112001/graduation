<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 10/22/2020
 * Time: 2:06 PM
 */

namespace App\Utils;


use App\Models\Order;
use App\Models\Product;
use Darryldecode\Cart\CartCollection;

class TiktokTrackingUtil
{
    public $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function addEvent($name, $params = [])
    {
        $this->events[$name] = $params;
    }

    public function removeEvent($name)
    {
        unset($this->events[$name]);
    }

    public static function instance()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new TiktokTrackingUtil();
        }
        return $instance;
    }

    public static function add($event, $params = [])
    {
        TiktokTrackingUtil::instance()->addEvent($event, $params);
    }

    public static function remove($event)
    {
        TiktokTrackingUtil::instance()->removeEvent($event);
    }


    /**
     * @param string $pageName
     * @param Product $product
     * @param int $value
     */
    public static function viewContent(string $pageName, Product $product, int $value)
    {
        $params = [
            'content_id' => $product['code'],
            'content_category' => $product->category->name,
            'content_name' => $pageName,
            'content_type' => 'product',
            'currency' => 'VND',
            'price' => $product['price'] ?? $product['price_old'],
            'value' => $value
        ];
        TiktokTrackingUtil::instance()->addEvent('ViewContent', $params);
    }

    /**
     * @param mixed $product
     * @param int $quantity
     * @return array
     */
    public static function addToCart($product, int $quantity = 1)
    {
        return $params = [
            'content_type' => 'product',
            'content_id' => $product['code'],
            'content_category' => $product->category->name,
            'content_name' => $product['name'],
            'currency' => 'VND',
            'value' => ($product['price'] ?? $product['price_old']) * $quantity,
            'quantity' => $quantity,
            'price' => $product['price'] ?? $product['price_old'],
        ];
    }

    /**
     * @param CartCollection $cart
     * @return array
     */
    public static function initiateCheckout($cart)
    {
        $contentIds = [];
        $contents = [];
        $value = 0;
        foreach ($cart->toArray() as $item) {
            $product = $item['associatedModel'];
            array_push($contentIds, $product['code']);
            array_push($contents, [
                'id' => $product['code'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'content_type' => 'product',
            ]);
            $value += $item['price'] * $item['quantity'];
        }
        $params = [
            'content_type' => 'product',
            'contents' => $contents,
            'content_category' => 'Checkout',
            'currency' => 'VND',
            'value' => $value
        ];
        return $params;
    }

    /**
     * @param Order $order
     */
    public static function purchase(Order $order)
    {
        $contents = [];
        $value = 0;
        foreach ($order->items as $item) {
            $product = $item->product;
            array_push($contents, [
                'id' => $product['code'],
                'quantity' => $item['quantity'],
                'price' => $item['sub_total'],
                'content_type' => 'product',
            ]);
            $value += $item['total'];
        }
        $params = [
            'content_name' => 'Đặt hàng',
            'content_type' => 'product',
            'contents' => $contents,
            'currency' => 'VND',
            'value' => $value
        ];
        TiktokTrackingUtil::instance()->addEvent('Purchase', $params);
    }

}
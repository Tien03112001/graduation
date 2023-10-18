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

class FacebookTrackingUtil
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
            $instance = new FacebookTrackingUtil();
        }
        return $instance;
    }


    public static function add($event, $params = [])
    {
        FacebookTrackingUtil::instance()->addEvent($event, $params);
    }

    public static function remove($event)
    {
        FacebookTrackingUtil::instance()->removeEvent($event);
    }

    /**
     * @param mixed $product
     * @param int $quantity
     * @return array
     */
    public static function addToCart($product, int $quantity = 1)
    {
        return [
            'content_ids' => [$product['code']],
            'content_name' => $product['name'],
            'content_type' => 'product',
            'contents' => [
                [
                    'id' => $product['code'],
                    'quantity' => $quantity,
                    'price' => $product['price']
                ]
            ],
            'currency' => 'VND',
            'value' => $product['price'] * $quantity
        ];
    }

    /**
     * @param string $pageName
     * @param Product $product
     * @param int $value
     */
    public static function viewContent(string $pageName, Product $product, int $value)
    {
        $contentIds = [$product['code']];
        $contents = [
            [
                'id' => $product['code'],
                'quantity' => 1,
                'price' => $product['price'] ?? $product['price_old']
            ]
        ];

        $params = [
            'content_ids' => $contentIds,
            'content_category' => $product->category->name,
            'content_name' => $pageName,
            'content_type' => 'product',
            'contents' => $contents,
            'currency' => 'VND',
            'value' => $value
        ];
        FacebookTrackingUtil::instance()->addEvent('ViewContent', $params);
    }

    /**
     * @param string $searchString
     * @param Product[] $products
     * @param $value
     */
    public static function search(string $searchString, array $products, int $value)
    {
        $contentIds = [];
        $contents = [];
        foreach ($products as $item) {
            array_push($contentIds, $item['code']);
            array_push($contents, [
                'id' => $item['code'],
                'quantity' => 1,
                'price' => $item['price']
            ]);
        }
        $params = [
            'search_string' => $searchString,
            'content_ids' => $contentIds,
            'content_category' => $item->category->name,
            'contents' => $contents,
            'currency' => 'VND',
            'value' => $value
        ];
        FacebookTrackingUtil::instance()->addEvent('Search', $params);
    }

    /**
     * @param $pageName
     * @param $value
     */
    public static function lead($pageName, $value)
    {
        $params = [
            'content_category' => 'Pages',
            'content_name' => $pageName,
            'currency' => 'VND',
            'value' => $value
        ];
        FacebookTrackingUtil::instance()->addEvent('Lead', $params);
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
                'price' => $item['price']
            ]);
            $value += $item['price'] * $item['quantity'];
        }
        $params = [
            'content_category' => $product->category->name,
            'content_ids' => $contentIds,
            'contents' => $contents,
            'content_type' => 'product',
            'num_items' => count($contentIds),
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
        $contentIds = [];
        $contents = [];
        $value = 0;
        foreach ($order->order_details as $item) {
            $product = $item->product;
            array_push($contentIds, $product['code']);
            array_push($contents, [
                'id' => $product['code'],
                'quantity' => $item['quantity'],
                'price' => $item['sub_total']
            ]);
            $value += $item['total'];
        }
        $params = [
            'content_ids' => $contentIds,
            'content_name' => 'Đặt hàng',
            'content_type' => 'product',
            'contents' => $contents,
            'num_items' => count($contentIds),
            'currency' => 'VND',
            'value' => $value
        ];
        FacebookTrackingUtil::instance()->addEvent('Purchase', $params);
    }

    public static function contact()
    {
        FacebookTrackingUtil::instance()->addEvent('Contact', []);
    }
}
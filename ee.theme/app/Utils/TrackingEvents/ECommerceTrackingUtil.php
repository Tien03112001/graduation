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
use Illuminate\Support\Facades\Session;

class ECommerceTrackingUtil
{
    public $events;
    public static $LIST_NAME_KEY = 'gtm_list_name';

    public function __construct()
    {
        $this->events = [];
    }

    public function add(array $params = [])
    {
        array_push($this->events, $params);
    }

    public function removeAll()
    {
        $this->events = [];
    }

    public static function instance()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new ECommerceTrackingUtil();
        }
        return $instance;
    }


    public static function addEvent(array $params = [])
    {
        self::instance()->add($params);
    }

    public static function removeAllEvents()
    {
        self::instance()->removeAll();
    }

    /**
     * @param array|null $productIds
     * @param string $pageType
     * @param int $value
     */
    public static function addEcommEvent($productIds, string $pageType, int $value)
    {
        $ecomm = [
            'ecomm_prodid' => $productIds,
            'ecomm_pagetype' => $pageType,
            'ecomm_totalvalue' => $value
        ];
        self::instance()->addEvent($ecomm);
    }

    /**
     * @param string $pageName
     * @param Product[] $products
     */
    public static function productImpressions(string $pageName, array $products)
    {
        $params = [
            'ecommerce' => [
                'currencyCode' => 'VND',
                'impressions' => []
            ]
        ];
        foreach ($products as $index => $p) {
            array_push($params['ecommerce']['impressions'], [
                'id' => $p->code,
                'name' => $p->name,
                'list' => $pageName,
                'brand' => $p->g_shopping->brand ?? 'Undefined',
                'category' => $p->category->name ?? 'Undefined',
                'position' => $index + 1,
                'price' => $p->price
            ]);
        }
        self::instance()->addEvent($params);
    }

    /**
     * @param Product $product
     */
    public static function productDetail(Product $product)
    {
        self::addEvent([
            'event' => 'productDetail',
            'ecommerce' => [
                'detail' => [
                    'actionField' => [
                        'list' => Session::get(self::$LIST_NAME_KEY, 'Direct')
                    ],
                    'products' => [
                        [
                            'id' => $product->code,
                            'name' => $product->name,
                            'brand' => $product->g_shopping->brand ?? 'Undefined',
                            'category' => $product->category->name ?? 'Undefined',
                            'price' => $product->price ?? $product->price_old
                        ]
                    ]
                ]
            ],
        ]);
        self::addEcommEvent([$product->code], 'product', $product->price ?? $product->price_old);
    }


    /**
     * @param array $product
     * @param int $quantity
     * @return array
     */
    public static function addToCart(array $product, int $quantity = 1)
    {
        $params = [
            'event' => 'addToCart',
            'ecommerce' => [
                'add' => [
                    'products' => [
                        [
                            'id' => $product['code'],
                            'name' => $product['name'],
                            'price' => $product['price'] ?? $product['price_old'],
                            'brand' => $product['g_shopping']['brand'] ?? 'Undefined',
                            'category' => $product['category'][0]['name'] ?? 'Undefined',
                            'quantity' => $quantity,
                        ]
                    ]
                ]
            ],
        ];
        return $params;
    }

    /**
     * @param array $product
     * @param int $quantity
     * @return array
     */
    public static function removeFromCart(array $product, int $quantity = 1)
    {
        $params = [
            'event' => 'removeFromCart',
            'ecommerce' => [
                'remove' => [
                    'products' => [
                        [
                            'id' => $product['code'],
                            'name' => $product['name'],
                            'price' => $product['price'] ?? $product['price_old'],
                            'brand' => $product['g_shopping']['brand'] ?? 'Undefined',
                            'category' => $product['category'][0]['name'] ?? 'Undefined',
                            'quantity' => $quantity,
                        ]
                    ]
                ]
            ],
        ];
        return $params;
    }

    /**
     * @param CartCollection $cart
     * @return array
     */
    public static function convertToCheckoutData(CartCollection $cart)
    {
        $products = [];
        foreach ($cart as $index => $item) {
            $product = $item['associatedModel'];
            array_push($products, [
                'name' => $product->name,
                'id' => $product->code,
                'price' => $product->price ?? $product->price_old,
                'brand' => $product['g_shopping']['brand'] ?? 'Undefined',
                'category' => $product->category->name ?? 'Undefined',
                'quantity' => $item['quantity']
            ]);
        }
        return [
            'event' => 'checkout',
            'ecommerce' => [
                'checkout' => [
                    'actionField' => [
                        'step' => 1,
                    ],
                    'products' => $products
                ]
            ],
        ];
    }

    /**
     * @param CartCollection $cart
     */
    public static function viewCart(CartCollection $cart)
    {
        $productIds = [];
        $total = 0;
        foreach ($cart as $item) {
            $product = $item['associatedModel'];
            array_push($productIds, $product->code);
            $total += $item['quantity'] * ($product->price ?? $product->price_old);
        }
        self::addEcommEvent($productIds, 'cart', $total);
    }

    /**
     * @param int $step
     * @param string $option
     */
    public static function setCheckoutOption(int $step, string $option)
    {
        self::addEvent([
            'event' => 'checkoutOption',
            'ecommerce' => [
                'checkout_option' => [
                    'actionField' => [
                        'step' => $step,
                        'option' => $option
                    ]
                ]
            ],
        ]);
    }

    /**
     * @param Order $order
     */
    public static function purchase(Order $order)
    {
        $products = [];
        $ecommProductIds = [];
        foreach ($order->items as $index => $item) {
            array_push($products, [
                'name' => $item->product->name,
                'id' => $item->product->code,
                'price' => $item->sub_total,
                'brand' => $item->product->g_shopping->brand ?? 'Undefined',
                'category' => $item->product->category->name,
                'quantity' => $item->quantity
            ]);
            array_push($ecommProductIds, $item->product->code);
        }
        self::addEvent([
            'event' => 'purchase',
            'ecommerce' => [
                'purchase' => [
                    'actionField' => [
                        'id' => $order->code,
                        'affiliation' => 'Online Store',
                        'revenue' => $order->total,
                        'shipping' => $order->ship_cost,
                    ],
                    'products' => $products
                ]
            ]
        ]);
        self::addEcommEvent($ecommProductIds, 'purchase', $order->total);
    }

    /**
     * @param Order $order
     */
    public static function refund(Order $order)
    {
        $params = [
            'transaction_id' => $order->code,
            'affiliation' => 'Online Store',
            'value' => $order->total,
            'currency' => 'VND',
            'shipping' => $order->ship_cost,
            'items' => []
        ];
        foreach ($order->items as $index => $item) {
            array_push($params['items'], [
                'id' => $item->product->code,
                'name' => $item->product->name,
                'list_name' => Session::get(self::$LIST_NAME_KEY, 'Direct'),
                'brand' => env('BRAND', 'Others'),
                'category' => $item->product->category->name,
                'list_position' => $index + 1,
                'price' => $item->product->price,
                'quantity' => $item->quantity
            ]);
        }
        self::addEvent($params);
    }
}
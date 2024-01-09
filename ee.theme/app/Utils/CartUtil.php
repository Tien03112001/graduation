<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 10/27/2022
 * Time: 14:06
 */

namespace App\Utils;


use App\Common\SingletonPattern;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartUtil extends SingletonPattern
{
    /* @var array */
    protected $items;
    protected $totalAmount;
    protected $totalQuantity;
    protected $productCount;
    protected $discountValue;
    protected $total;
    protected $shippingFee;

    /* @var array */
    protected $voucher;

    protected $sessionKey = 'session.cart';

    /**
     * @return CartUtil
     */
    public static function getInstance()
    {
        return new CartUtil();
    }

    protected function __construct()
    {
        $cart = $this->getSession();
        $this->items = $cart['items'];
        $this->voucher = $cart['voucher'];
        $this->shippingFee = $cart['shippingFee'];
        $this->refreshCart();
    }

    /**
     * Lấy dữ liệu từ session
     * @return array
     */
    public function getSession()
    {
        if (Session::has($this->sessionKey)) {
            $items = Session::get($this->sessionKey, null);
            return json_decode($items, true);
        }
        return [
            'items' => [],
            'voucher' => null,
            'shippingFee' => 0,
        ];
    }

    /**
     * Lưu lại session
     * @return $this
     */
    public function saveSession()
    {
        $value = json_encode([
            'items' => $this->items,
            'voucher' => $this->voucher,
            'shippingFee' => $this->shippingFee,
        ]);
        Session::put($this->sessionKey, $value);
        return $this;
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     * @param Product $p
     * @param int $quantity
     * @param string $size
     * @param float $unitPrice
     * @return $this
     */
    public function addItem(Product $p, int $quantity , string $size, float $unitPrice = null)
    {
        if (isset($this->items[$p->id . ' ' . $size])) {
            if ($this->items[$p->id . ' ' . $size]['size'] == $size) {
                $this->items[$p->id . ' ' . $size]['quantity'] += $quantity;
            } else {
                $item = [
                    'item' => $p->toArray(),
                    // 'unit_price' => $unitPrice ?? $p->price,
                    'quantity' => $quantity,
                    'size' => $size,
                ];
                if($p['promotions']->first()!=null)
                {
                    if( $this->getTotalQuantity()+$quantity<$p['promotions']->first()->min_products_count)
                    {

                        $item['unit_price'] = $p->price;
                    }
                    else{
                        $item['unit_price']=$p['promotions']->first()->same_price;
                    };
                }
                $item['amount'] = $item['unit_price'] * $quantity;
                $this->items[$p->id . ' ' . $size] = $item;

            }
        } else {
            $item = [
                'item' => $p->toArray(),
                // 'unit_price' => $unitPrice ?? $p->price,
                'quantity' => $quantity,
                'size' => $size,
            ];
            if($p['promotions']->first()!=null)
            {
                if( $this->getTotalQuantity()+$quantity<$p['promotions']->first()->min_products_count)
                {

                    $item['unit_price'] = $p->price;
                }
                else{
                    $item['unit_price']=$p['promotions']->first()->same_price;
                };
            }
            else{
                $item['unit_price'] = $p->price;
            }
            $item['amount'] = $item['unit_price'] * $quantity;
            $this->items[$p->id . ' ' . $size] = $item;
        }
        return $this->refreshCart()->saveSession();
    }


    /**
     * Xóa sản phẩm vào giỏ hàng
     * @param Product $p
     * @return $this
     * @throws \Exception
     */
    public function removeItem(string $p)
    {
        if (array_key_exists($p, $this->items)) {
            unset($this->items[$p]);
        } else {
            throw  new \Exception('Sản phẩm không tồn tại trong giỏ hàng');
        }
        return $this->refreshCart()->saveSession();
    }

    /**
     * Xóa sản phẩm vào giỏ hàng bằng ID
     * @param int $id
     * @return $this
     */
    public function removeItemById(int $id)
    {
        if (array_key_exists($id, $this->items)) {
            unset($this->items[$id]);
        }
        return $this->refreshCart()->saveSession();
    }

    /**
     * update số lượng
     * @param Product $p
     * @param int $quantity
     * @return $this
     */
    public function updateQuantity(Product $p, int $quantity)
    {
        if (array_key_exists($p->id, $this->items)) {
            $this->items[$p->id]['quantity'] = $quantity;
        } else {
            $this->addItem($p, $quantity);
        }
        return $this->refreshCart()->saveSession();
    }


    /**
     * Update số lượng bằng ID
     * @param int $id
     * @param int $quantity
     * @return CartUtil
     * @throws \Exception
     */
    public function updateQuantityById(int $id, int $quantity)
    {
        if (array_key_exists($id, $this->items)) {
            $this->items[$id]['quantity'] = $quantity;
        } else {
            $p = Product::query()->find($id);
            if (empty($p)) {
                throw  new \Exception('Sản phẩm không tồn tại');
            } else {
                $this->updateQuantity($p, $quantity);
            }
        }
        return $this->refreshCart()->saveSession();
    }

    public function setShippingFee(float $fee)
    {
        $this->shippingFee = $fee;
        return $this->refreshCart()->saveSession();
    }

    /**
     * Thêm voucher
     * @param $voucher
     */
    public function applyVoucher($voucher)
    {
        if (empty($voucher)) {
            return;
        }
        $this->voucher = $voucher;

        $apply = $voucher['min_order_value'] <= $this->totalAmount
            && $voucher['min_products_count'] < $this->totalQuantity;

        //apply voucher
        if ($apply) {
            //tính giảm tiền giá trị voucher
            $this->discountValue = $this->totalAmount * $voucher['discount_percent'] + $voucher['discount_value'];
            //free ship
            if ($voucher['free_ship'] == 1) {
                $this->shippingFee = 0;
            }
        }

        //nếu giảm trừ lớn hơn tổng tiền hàng thì giảm trừ = tổng tiền hàng
        if ($this->discountValue > $this->totalAmount) {
            $this->discountValue = $this->totalAmount;
        }

        $this->total = $this->totalAmount - $this->discountValue + $this->shippingFee;
    }

    /**
     * Tính lại thông tin giỏ hàng
     * @return $this
     */
    public function refreshCart()
    {
        $this->totalAmount = 0;
        $this->totalQuantity = 0;
        $this->discountValue = 0;
        $this->productCount = count($this->items);
        foreach ($this->items as $item) {
            // $item['amount'] = $item['quantity'] * $item['unit_price'];
            // $this->totalAmount += $item['amount'];
            $this->totalQuantity += $item['quantity'];

        }
        foreach($this->items as $item){

            if($item['item']['promotions']!=null)
            {
                if( $this->totalQuantity<$item['item']['promotions'][0]['min_products_count'])
                {
                    $item['unit_price'] = $item['item']['price'];
                    $item['amount'] = $item['quantity'] * $item['unit_price'];
                    $this->items[key($this->items)]['unit_price'] = $item['item']['price'];
                    $this->items[key($this->items)]['amount'] = $item['quantity'] * $item['unit_price'];
                }
                else{
                    $item['unit_price']=$item['item']['promotions'][0]['same_price'];
                    $this->items[key($this->items)]['unit_price'] = $item['item']['promotions'][0]['same_price'];
                    $this->items[key($this->items)]['amount'] = $item['item']['promotions'][0]['same_price']*$item['quantity'];

                };


            }
            else{
                $item['unit_price']=$item['item']['price'];
                $item['amount'] = $item['quantity'] * $item['unit_price'];
            }

            $this->totalAmount += $item['amount'];
        }

        $this->total = $this->totalAmount - $this->discountValue + $this->shippingFee;
        return $this;
    }

    /**
     * Update tăng 1 sản phẩm bằng ID
     * @param string $id
     * @return CartUtil
     * @throws \Exception
     */
    public function pushQuantityById(string $id)
    {
        $this->items[$id]['quantity']++;
        $this->items[$id]['amount'] = $this->items[$id]['unit_price'] * $this->items[$id]['quantity'];
        return $this->refreshCart()->saveSession();
    }

    /**
     * Update giảm 1 sản phẩm bằng ID
     * @param int $id
     * @return CartUtil
     * @throws \Exception
     */
    public function minusQuantityById(string $p)
    {
        $this->items[$p]['quantity']--;
        $this->items[$p]['amount'] = $this->items[$p]['unit_price'] * $this->items[$p]['quantity'];
        if ($this->items[$p]['quantity'] == 0) {
            $this->removeItem($p);
        }
        return $this->refreshCart()->saveSession();
    }

    /**
     * @return array|null
     */
    public function getItems()
    {
        return $this->items;
    }

    public function flushCart()
    {
        Session::forget($this->sessionKey);
        return $this;

    }

    /**
     * @return mixed
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @return mixed
     */
    public function getTotalQuantity()
    {
        return $this->totalQuantity;
    }

    /**
     * @return mixed
     */
    public function getProductCount()
    {
        return $this->productCount;
    }

    /**
     * @return mixed
     */
    public function getDiscountValue()
    {
        return $this->discountValue;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function getShippingFee()
    {
        return $this->shippingFee;
    }

    /**
     * @return array|null
     */
    public function getVoucher()
    {
        return $this->voucher;
    }

    public function isEmpty(): bool
    {
        if (count($this->getItems())) {
            return false;
        }
        return true;
    }

}

<?php

namespace App\Http\Controllers\web;

use App\Common\FacebookConversion\FacebookConversionEvent;
use App\Common\SEO\SEOPage;
use App\Common\WhereClause;
use App\Http\Controllers\RestController;
use App\Jobs\FacebookConversionJob;
use App\Models\Voucher;
use App\Models\Product;
use App\Repository\OrderRepositoryInterface;
use App\Repository\PaymentMethodRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\PromotionRepositoryInterface;
use App\Repository\ProvinceRepositoryInterface;
use App\Repository\VoucherRepositoryInterface;
use App\Utils\CartUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


class CartController extends RestController
{
    protected $voucherRepository;
    protected $productRepository;
    protected $paymentMethodRepository;
    protected $provinceRepository;
    protected $promotionRepository;

    public function __construct(OrderRepositoryInterface $repository, VoucherRepositoryInterface $voucherRepository,
                                PaymentMethodRepositoryInterface $paymentMethodRepository,
                                ProvinceRepositoryInterface $provinceRepository,PromotionRepositoryInterface $promotionRepository,
                                ProductRepositoryInterface $productRepository)
    {
        parent::__construct($repository);
        $this->voucherRepository = $voucherRepository;
        $this->productRepository = $productRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->provinceRepository = $provinceRepository;
        $this->promotionRepository = $promotionRepository;
    }

    public function index(Request $request)
    {
        $cart = CartUtil::getInstance();
        foreach ($cart->getItems() as $key => $value) {
            if ($this->productRepository->findById(explode(' ', $key)[0], [], []) == null) {
                $cart->removeItem($key);
            }
        }
        if ($cart->getItems() != null) {
            $products = (new Product())
                ->wherePublished(true)
                ->where('category_id', $cart->getItems()[array_key_first($cart->getItems())]['item']['category_id'])
                ->where('id', '<>', array_key_first($cart->getItems()))
                ->orderBy('id', 'DESC')
                ->with('article', 'tags', 'meta', 'category','promotions')
                ->limit(8)
                ->get();
        } else {
            $products = $this->productRepository->getAll();
        }

        $page = new SEOPage('Giỏ hàng');

        return view('theme.order.cart', compact('cart', 'products', 'page'));
    }

    public function addItem($id, Request $request)
    {
        if (!$request->has('size-radio')) {
            return Redirect::back()->with('msg_error', 'Thêm vào giỏ hàng thất bại , vui lòng chọn size');
        } else {
            $product = $this->productRepository->findById($id,['promotions']);
            if (isset($product)) {
                $quantity = $request->input('quantity', 1);
                CartUtil::getInstance()->addItem($product, $quantity, $request->input('size-radio'),$product->sale_price);
            } else {
                return redirect()->back()->withErrors(['errors' => 'Sản phẩm không tồn tại']);
            }

            FacebookConversionJob::dispatch(FacebookConversionEvent::createAddToCartEvent($product, $request));

            return Redirect::back()->with('msg_success', 'Thêm vào giỏ hàng thành công');
        }
    }

    public function buyNow($id, Request $request)
    {
        if (!$request->has('size-radio')) {
            return Redirect::back()->with('msg_error', 'Mua ngay thất bại , vui lòng chọn size');
        } else {
            $paymentMethods = $this->paymentMethodRepository->get([
                WhereClause::query('enable', 1),
            ]);
            $manual = $this->paymentMethodRepository->find([WhereClause::query('name', 'Chuyển khoản')]);
            $manual_config = json_decode($manual->config, true);
            $provinces = $this->provinceRepository->get([]);
            $product = $this->productRepository->findById($id);
            if (isset($product)) {
                $quantity = $request->input('quantity', 1);
                $page = new SEOPage('Checkout giỏ hàng');

                FacebookConversionJob::dispatch(FacebookConversionEvent::createInitiateCheckoutEvent(CartUtil::getInstance(), $request));

                return view('theme.order.checkout', compact('product', 'quantity', 'paymentMethods', 'manual_config', 'provinces', 'page'));
            } else {
                return redirect()->back()->withErrors(['errors' => 'Sản phẩm không tồn tại']);
            }

        }

    }

    public function buyItem($id, Request $request)
    {
        if (!$request->has('size-radio')) {
            return Redirect::back()->with('msg_error', 'Thêm vào giỏ hàng thất bại , vui lòng chọn size');
        }
        $product = $this->productRepository->findById($id);
        if (isset($product)) {
            $quantity = $request->input('quantity', 1);
            CartUtil::getInstance()->addItem($product, $quantity, $request->input('size-radio'));
        } else {
            return redirect()->back()->withErrors(['errors' => 'Sản phẩm không tồn tại']);
        }

        FacebookConversionJob::dispatch(FacebookConversionEvent::createAddToCartEvent($product, $request));

        return redirect()->to('/cart');
    }

    public function removeItem($id, Request $request)
    {
        try {
            CartUtil::getInstance()->removeItem($id);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }
        return redirect()->back();
    }

    public function updateQuantity($id, Request $request)
    {
        if ($request->has('quantity')) {
            try {
                CartUtil::getInstance()->updateQuantityById($id, $request->quantity);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['errors' => 'Không thể cập nhật số lượng']);
            }
        }
        return redirect()->back();
    }

    public function applyVoucher(Request $request)
    {

        $validator = $this->validateRequest($request, [
            'code' => 'required|max:255',
        ]);
        if ($validator) {
            return redirect()->back()->withErrors(['errors' => 'Không nhập mã voucher']);
        }
        $code = $request->input('code');
        $voucher = $this->voucherRepository->find([
            WhereClause::query('code', $code)
        ]);
        if (empty($voucher) || !($voucher instanceof Voucher)) {
            return redirect()->back()->withErrors(['errors' => 'Mã voucher không hợp lệ']);
        }

        if (!$voucher->enable || $voucher->quantity <= 0) {
            Session::flash('errors', 'Mã voucher không hợp lệ');
            return redirect()->back()->withErrors(['errors' => 'Mã voucher không hợp lệ']);
        }
        CartUtil::getInstance()->applyVoucher($voucher->toArray());
        return redirect()->back()->withInput();
    }

    public function checkout(Request $request)
    {
        if (CartUtil::getInstance()->getItems() == []) {
            return Redirect::back()->with('msg_error', 'Giỏ hàng trống, vui lòng thêm sản phẩm vào giỏ hàng');
        }
        $paymentMethods = $this->paymentMethodRepository->get([
            WhereClause::query('enable', 1),
        ]);
        $manual = $this->paymentMethodRepository->find([WhereClause::query('name', 'Chuyển khoản')]);
        $manual_config = json_decode($manual->config, true);
        $provinces = $this->provinceRepository->get([]);
        $cart = CartUtil::getInstance();
        $page = new SEOPage('Checkout giỏ hàng');
        $promotions=$this->promotionRepository->get([WhereClause::query('enable', 1)],null,['products']);
        FacebookConversionJob::dispatch(FacebookConversionEvent::createInitiateCheckoutEvent(CartUtil::getInstance(), $request));

        return view('theme.order.checkout', compact('cart', 'paymentMethods', 'provinces', 'manual_config', 'page','promotions'));
    }

    public function pushQuantity($id, Request $request)
    {
        CartUtil::getInstance()->pushQuantityById($id);
        return redirect()->back();
    }

    public function minusQuantity($id)
    {
        CartUtil::getInstance()->minusQuantityById($id);
        return redirect()->back();
    }

}

import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { OrderService } from '../order.service';
import { OrderMeta } from '../order.meta';
import { ShippingProvinceMeta } from '../../shipping-province/shipping-province.meta';
import { ShippingDistrictMeta } from '../../shipping-district/shipping-district.meta';
import { AbstractModalComponent, FieldForm, ObjectUtil } from '../../../core';
import { ChannelService } from '../../channel/channel.service';
import { ShippingProvinceService } from '../../shipping-province/shipping-province.service';
import { PaymentTypeService } from '../../payment-type/payment-type.service';
import { VoucherService } from '../../voucher/voucher.service';
import { CartItemMeta } from '../../sale-order/cart-item.meta';
import { ProductMeta } from '../../product/product.meta';
import { ProductService } from '../../product/product.service';


@Component({
  selector: 'app-order-edit',
  templateUrl: './order-edit.component.html',
  styleUrls: ['./order-edit.component.css'],
  providers: [OrderService, ChannelService, ShippingProvinceService, PaymentTypeService, VoucherService, ProductService]
})
export class OrderEditComponent extends AbstractModalComponent<OrderMeta> {

  arrProduct: CartItemMeta[] = [];

  productList: ProductMeta[];

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllProvinces() {
    return this.provinceService.loadAll();
  }

  loadVouchers() {
    return this.voucherService.loadAll();
  }

  loadAllPaymentTypes() {
    return this.paymentService.loadAll()
    .map(data => {
      return data.map(v => {
        return Object.create({ text: v.name, value: v.name });
      });
    });
  }

  loadAllProducts() {
    return this.productService.loadAll();
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      customer_name: new FormControl(null, [Validators.maxLength(255), Validators.required, Validators.pattern('^(?=.*[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$')]),
      customer_phone: new FormControl(null, [Validators.required, Validators.pattern('^(0)[0-9]{9}$'), Validators.maxLength(10)]),
      customer_address: new FormControl(null, [Validators.maxLength(255), Validators.required, Validators.pattern('^(?=.*[a-zA-Z0-9\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[^-\\s][a-zA-Z-0-9\.,/đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$')]),
      province: new FormControl(null, [Validators.required]),
      district: new FormControl(null, [Validators.required]),
      ward: new FormControl(null, [Validators.required]),
      province_id: new FormControl(null),
      district_id: new FormControl(null),
      ward_id: new FormControl(null),
      request: new FormControl(null, Validators.maxLength(255)),
      note: new FormControl(null, Validators.maxLength(255)),
      product: new FormControl(null),
      shipping_fee: new FormControl(null, [Validators.required, Validators.min(0)]),
      voucher: new FormControl(null),
      promotion: new FormControl(null),
      total_amount: new FormControl({ value: null, disabled: true }),
      payment_type: new FormControl(null, [Validators.required]),
      cod_fee: new FormControl(null, [Validators.required, Validators.min(0)]),
      banking_sms: new FormControl(null, Validators.maxLength(255)),
      banking_img: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên khách hàng *', 'customer_name', 'Nhập kí tự'),
      FieldForm.createNumberInput('Số điện thoại khách hàng *', 'customer_phone', 'Nhập số'),
      FieldForm.createTextInput('Địa chỉ khách hàng *', 'customer_address', 'Nhập kí tự'),
      FieldForm.createSingleSelect2('Tỉnh/TP *', 'province', 'Nhập tên tỉnh/tp', 'loadAllProvinces'),
      FieldForm.createSingleSelect2('Huyện/Quận *', 'district', 'Nhập tên huyện/quận', []),
      FieldForm.createSingleSelect2('Xã/Phường *', 'ward', 'Nhập tên xã/phường', []),
      FieldForm.createTextArea('Yêu cầu của khách hàng', 'request', 'Nhập kí tự', 2),
      FieldForm.createTextArea('Ghi chú', 'note', 'Nhập kí tự', 5),
      FieldForm.createSingleSelect2('Sản phẩm', 'product', 'Chọn một', 'loadAllProducts'),
      FieldForm.createNumberInput('Phí vận chuyển *', 'shipping_fee', 'Nhập số'),
      FieldForm.createSingleSelect2('Mã giảm giá', 'voucher', 'Nhập mã', []),
      FieldForm.createSingleSelect2('Chương trình khuyến mãi', 'promotion', 'Nhập mã' , []),
      FieldForm.createNumberInput('Tổng tiền', 'total_amount', '0'),
      FieldForm.createSingleSelect2('Hình thức thanh toán *', 'payment_type', 'Bắt buộc', 'loadAllPaymentTypes'),
      FieldForm.createNumberInput('Phí CoD', 'cod_fee', 'Nhập số'),
      FieldForm.createTextArea('Thông tin thanh toán', 'banking_sms', 'Nhập kí tự', 5),
      FieldForm.createFileInput('Tải ảnh', 'banking_img', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
    ];
  }

  public setFormValue() {
    let item: any = ObjectUtil.clone(this.model);
    item.province = [];
    item.district = [];
    item.ward = [];
    let provinceObj = this.fields[3].data.filter(v => v.id == this.model.province['id']);
    if (provinceObj.length > 0) {
      item.province = provinceObj;
      this.fields[4].data = provinceObj[0]['districts'];
      let districtObj = this.fields[4].data.filter(v => v.id == this.model.district['id']);
      if (districtObj.length > 0) {
        item.district = districtObj;
        this.fields[5].data = districtObj[0]['wards'];
        let wardObj = this.fields[5].data.filter(v => v.id == this.model.ward['id']);
        if (districtObj.length > 0) {
          item.ward = wardObj;
        }
      }
    }
    this.formGroup.patchValue(ObjectUtil.mergeValue(this.formGroup.value, item));

    for (let i of this.model.order_details) {
      let cartItemMeta = new CartItemMeta();
      cartItemMeta.product = i.product;
      cartItemMeta.quantity = i.quantity;
      cartItemMeta.unit_price = i.price;
      cartItemMeta.variant_id = i.variant_id;
      this.arrProduct.push(cartItemMeta);
      this.updateCart();
    }
  }

  loaded(): void {
  }

  constructor(
    service: OrderService,
    modal: BsModalRef,
    builder: FormBuilder,
    private provinceService: ShippingProvinceService,
    private paymentService: PaymentTypeService,
    private voucherService: VoucherService,
    private productService: ProductService
  ) {
    super(service, modal, builder);
  }

  edit() {
    let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    item.province_id = item.province[0].id;
    item.district_id = item.district[0].id;
    item.ward_id = item.ward[0].id;
    item.product = JSON.stringify(this.arrProduct);
    this.service.updateWithImage(item).subscribe(res => {
      this.service.toastSuccessfullyEdited();
      this.close(ObjectUtil.combineValue(this.model, res));
    }, () => this.service.toastFailedEdited());
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['province'].valueChanges.subscribe((value: ShippingProvinceMeta[]) => {
      if (value && value.length > 0) {
        this.formGroup.controls['district'].setValue(null);
        this.formGroup.controls['ward'].setValue(null);
        this.fields[4].data = value[0].districts;
        this.fields[5].data = [];
      }
    });
    this.formGroup.controls['district'].valueChanges.subscribe((value: ShippingDistrictMeta[]) => {
      if (value && value.length > 0) {
        this.formGroup.controls['ward'].setValue(null);
        this.fields[5].data = value[0].wards;
      }
    });
    this.formGroup.controls['shipping_fee'].valueChanges.subscribe((value: number) => {
      this.updateCart();
    });
  }

  checkArr(variant, i) {
    const test = this.arrProduct.filter(p => p.variant_id == variant);
    if (test.length >= 2) {
      test[0].quantity = test[0].quantity + test[1].quantity;
      this.arrProduct.splice(i, 1);
      this.updateCart();
    }
  }

  addProduct(productList: ProductMeta[]) {
    if (productList && productList.length > 0) {
      this.arrProduct.push({
        product: productList[0],
        variant_id: 0,
        unit_price: productList[0].sale_price,
        quantity: 1,
      });
      this.updateCart();
    }
  }


  updateCart() {
    let amount = 0;
    this.arrProduct.map(v => v.unit_price * v.quantity).forEach(v => {
      amount += v;
    });
    amount += +this.formGroup.get('shipping_fee').value;
    this.formGroup.get('total_amount').setValue(amount);
  }

  deleteArr(i) {
    this.arrProduct.splice(i, 1);
    this.updateCart();
  }

}

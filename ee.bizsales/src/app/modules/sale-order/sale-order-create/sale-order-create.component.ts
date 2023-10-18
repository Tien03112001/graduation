import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { SaleOrderMeta } from '../sale-order.meta';
import { SaleOrderService } from '../sale-order.service';
import { SaleCustomerService } from '../../sale-customer/sale-customer.service';
import { SaleCustomerMeta } from '../../sale-customer/sale-customer.meta';
import { ShippingProvinceMeta } from '../../shipping-province/shipping-province.meta';
import { ShippingDistrictMeta } from '../../shipping-district/shipping-district.meta';
import { AbstractModalComponent, FieldForm, ObjectUtil } from '../../../core';
import { DefinitionService } from '../../definition/definition.service';
import { ChannelService } from '../../channel/channel.service';
import { PaymentTypeService } from '../../payment-type/payment-type.service';
import { ShippingProvinceService } from '../../shipping-province/shipping-province.service';
import { VoucherService } from '../../voucher/voucher.service';
import { ProductService } from '../../product/product.service';
import { ProductMeta } from '../../product/product.meta';
import { CartItemMeta } from '../cart-item.meta';
import { VoucherMeta } from '../../voucher/voucher.meta';

@Component({
  selector: 'app-order-create',
  templateUrl: './sale-order-create.component.html',
  styleUrls: ['./sale-order-create.component.css'],
  providers: [SaleOrderService, SaleCustomerService, ChannelService, ShippingProvinceService, PaymentTypeService, VoucherService, ProductService]
})
export class SaleOrderCreateComponent extends AbstractModalComponent<SaleOrderMeta> {

  arrProduct: CartItemMeta[] = [];

  productList: ProductMeta[];

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllPaymentTypes() {
    return this.defService.loadAllPaymentTypes().map(data => {
      return data.map(v => {
        return Object.create({ text: v.name, value: v.id });
      });
    });
  }

  loadVouchers() {
    return this.voucherService.loadAll();
  }

  loadAllChannels() {
    return this.defService.loadAllChannels().map(data => {
      return data.map(v => {
        return Object.create({ text: v.name, value: v.value });
      });
    });
  }

  loadAllProvinces() {
    return this.defService.loadAllProvinces();
  }

  loadAllProducts() {
    return this.productService.loadAll();
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      channel: new FormControl(null, [Validators.required]),
      customer_name: new FormControl(null, [Validators.maxLength(255), Validators.required, Validators.pattern('^(?=.*[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$')]),
      customer_phone: new FormControl(null, [Validators.required, Validators.maxLength(10), Validators.pattern('^(0)[0-9]{9}$')]),
      customer_address: new FormControl(null, [Validators.maxLength(255), Validators.required]),
      province: new FormControl(null, [Validators.required]),
      district: new FormControl(null, [Validators.required]),
      ward: new FormControl(null, [Validators.required]),
      customer_request: new FormControl(null, Validators.maxLength(255)),
      note: new FormControl(null, Validators.maxLength(255)),
      product: new FormControl(null),
      ship_fee: new FormControl(null, [Validators.required, Validators.min(0)]),
      voucher: new FormControl(null),
      voucher_list: new FormControl(null),
      amount: new FormControl(null),
      total_amount: new FormControl({ value: null, disabled: true }),
      payment_type: new FormControl(null, [Validators.required]),
      cod_fee: new FormControl(0, [Validators.required, Validators.min(0)]),
      banking_sms: new FormControl(null, Validators.maxLength(255)),
      banking_img: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSelect('Kênh *', 'channel', 'Bắt buộc', 'loadAllChannels'),
      FieldForm.createTextInput('Tên khách hàng *', 'customer_name', 'Nhập kí tự'),
      FieldForm.createNumberInput('Số điện thoại khách hàng *', 'customer_phone', 'Nhập số'),
      FieldForm.createTextInput('Địa chỉ khách hàng *', 'customer_address', 'Nhập kí tự'),
      FieldForm.createSingleSelect2('Tỉnh/TP *', 'province', 'Nhập tên tỉnh/tp', 'loadAllProvinces'),
      FieldForm.createSingleSelect2('Huyện/Quận *', 'district', 'Nhập tên huyện/quận', []),
      FieldForm.createSingleSelect2('Xã/Phường *', 'ward', 'Nhập tên xã/phường', []),
      FieldForm.createTextArea('Yêu cầu của khách hàng', 'customer_request', 'Nhập kí tự', 2),
      FieldForm.createTextArea('Ghi chú', 'note', 'Nhập kí tự', 5),
      FieldForm.createSingleSelect2('Sản phẩm', 'product', 'Chọn một', 'loadAllProducts'),
      FieldForm.createNumberInput('Phí vận chuyển *', 'ship_fee', 'Nhập số'),
      FieldForm.createSingleSelect2('Mã giảm giá', 'voucher_list', 'Chọn một', 'loadVouchers'),
      FieldForm.createNumberInput('Tổng tiền', 'total_amount', '0'),
      FieldForm.createSelect('Hình thức thanh toán *', 'payment_type', 'Bắt buộc', 'loadAllPaymentTypes'),
      FieldForm.createNumberInput('Phí CoD', 'cod_fee', 'Nhập số'),
      FieldForm.createTextArea('Thông tin thanh toán', 'banking_sms', 'Nhập kí tự', 5),
      FieldForm.createFileInput('Tải ảnh', 'banking_img', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: SaleOrderService,
    modal: BsModalRef,
    builder: FormBuilder,
    private customerService: SaleCustomerService,
    private productService: ProductService,
    private voucherService: VoucherService,
    private defService: DefinitionService
  ) {
    super(service, modal, builder);
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['customer_phone'].valueChanges.debounceTime(1000).subscribe((value: string) => {
      if (value) {
        this.customerService.loadByParams({ phone: value }).subscribe((customers: SaleCustomerMeta[]) => {
          if (customers.length > 0) {
            let c: SaleCustomerMeta = customers[0];
            let provinceObj = this.fields[4].data.filter(v => v.name == c.province);
            let districtObj, wardObj;
            if (provinceObj.length > 0) {
              this.fields[5].data = provinceObj[0]['districts'];
              districtObj = this.fields[5].data.filter(v => v.name == c.district);
              if (districtObj.length > 0) {
                this.fields[6].data = districtObj[0]['wards'];
                wardObj = this.fields[6].data.filter(v => v.name == c.ward);
              }
            }
            let dataForm: any = {
              customer_name: c.name,
              customer_address: c.address,
              province: provinceObj,
              district: districtObj,
              ward: wardObj
            };
            Object.keys(dataForm).forEach(v => this.setFormValueByKey(v, dataForm[v], { emitEvent: false }));
          }
        });
      }
    });
    this.formGroup.controls['province'].valueChanges.subscribe((value: ShippingProvinceMeta[]) => {
      if (value && value.length > 0) {
        this.formGroup.controls['district'].setValue(null);
        this.formGroup.controls['ward'].setValue(null);
        this.fields[5].data = value[0].districts;
        this.fields[6].data = [];
      }
    });
    this.formGroup.controls['district'].valueChanges.subscribe((value: ShippingDistrictMeta[]) => {
      if (value && value.length > 0) {
        this.formGroup.controls['ward'].setValue(null);
        this.fields[6].data = value[0].wards;
      }
    });
    this.formGroup.controls['ship_fee'].valueChanges.subscribe((value: number) => {
      this.updateCart();
    });
    this.formGroup.controls['voucher_list'].valueChanges.subscribe(value => {
      this.updateCart();
    });
  }

  create() {
    let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    item.province = this.getFormValue('province')[0].name;
    item.district = this.getFormValue('district')[0].name;
    item.ward = this.getFormValue('ward')[0].name;
    item.product = JSON.stringify(this.arrProduct);
    this.service.storeWithImage(item).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
  }

  save() {
    let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    if (this.getFormValue('province') != null) {
      item.province = this.getFormValue('province')[0].name;
    }
    if (this.getFormValue('district') != null) {
      item.district = this.getFormValue('district')[0].name;
    }
    if (this.getFormValue('ward') != null) {
      item.ward = this.getFormValue('ward')[0].name;
    }
    if (this.arrProduct != null) {
      item.product = JSON.stringify(this.arrProduct);
    }
    item.status = 'Lưu nháp';
    this.service.storeWithImage(item).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
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

  checkArr(variant, i) {
    const test = this.arrProduct.filter(p => p.variant_id == variant);
    if (test.length >= 2) {
      test[0].quantity = test[0].quantity + test[1].quantity;
      this.arrProduct.splice(i, 1);
      this.updateCart();
    }
  }

  updateCart() {
    let amount = 0;
    this.arrProduct.map(v => v.unit_price * v.quantity).forEach(v => {
      amount += v;
    });
    if (this.formGroup.get('voucher_list').value && this.formGroup.get('voucher_list').value.length > 0) {
      let v: VoucherMeta = this.formGroup.get('voucher_list').value[0];
      if (amount < v.min_order_value) {
        this.service.toastError('Không đủ điều kiện áp dụng');
      } else {
        amount = amount - (amount * v.discount_percent / 100);
        if (amount <= v.discount_value) {
          amount = 0;
        } else {
          amount = amount - v.discount_value;
        }
        this.formGroup.controls['voucher'].setValue(v.id);
      }
    }
    this.formGroup.controls['amount'].setValue(amount);
    amount += +this.formGroup.get('ship_fee').value;
    this.formGroup.get('total_amount').setValue(amount);
    this.formGroup.controls['total_amount'].setValue(amount);
  }

  deleteArr(i) {
    this.arrProduct.splice(i, 1);
    this.updateCart();
  }

}

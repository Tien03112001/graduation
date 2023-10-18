import { Component } from '@angular/core';
import { AbstractControl, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { SaleOrderMeta } from '../sale-order.meta';
import { SaleOrderService } from '../sale-order.service';
import { AbstractModalComponent, FieldForm, ObjectUtil } from '../../../core';
import { PaymentTypeService } from '../../payment-type/payment-type.service';

@Component({
  selector: 'app-order-verify',
  templateUrl: './sale-order-verify.component.html',
  styleUrls: ['./sale-order-verify.component.css'],
  providers: [SaleOrderService, PaymentTypeService]
})
export class SaleOrderVerifyComponent extends AbstractModalComponent<SaleOrderMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadPaymentType() {
    return this.paymentService.loadAll()
      .map(data => {
        return data.map(v => {
          return Object.create({ text: v.name, value: v.id });
        });
      });
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      payment_type: new FormControl(null, Validators.required),
      cod_fee: new FormControl(0, [Validators.required, Validators.min(0)]),
      banking_sms: new FormControl(null),
      banking_img: new FormControl(null),
      note: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSelect('Hình thức thanh toán *', 'payment_type', 'Bắt buộc', 'loadPaymentType', '', 'name', 'value'),
      FieldForm.createNumberInput('Phí CoD *', 'cod_fee', 'Nhập số'),
      FieldForm.createTextArea('Giao dịch ngân hàng', 'banking_sms', 'Nhập kí tự', 5),
      FieldForm.createFileInput('Tải ảnh', 'banking_img', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
      FieldForm.createTextArea('Ghi chú', 'note', 'Nhập kí tự', 5),
    ];
  }

  public setFormValue() {
    let item: any = ObjectUtil.clone(this.model);
    this.formGroup.get(this.fields[0].formControl).setValue([this.model.payment_type]);
    this.formGroup.patchValue(ObjectUtil.mergeValue(this.formGroup.value, item));
  }

  loaded(): void {
  }

  constructor(
    service: SaleOrderService,
    modal: BsModalRef,
    builder: FormBuilder,
    private paymentService: PaymentTypeService,
  ) {
    super(service, modal, builder);
  }

  verify() {
    let item: SaleOrderMeta = this.prepareParams();
    (<SaleOrderService>this.service).verifyWithImage(item).subscribe(res => {
      this.service.toastSuccessfully('Xác nhận');
      this.close(res);
    }, () => this.service.toastFailed('Xác nhận'));
  }
}

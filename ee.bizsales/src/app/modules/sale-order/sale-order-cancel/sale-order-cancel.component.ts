import {Component} from '@angular/core';
import {AbstractControl, FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {SaleOrderMeta} from '../sale-order.meta';
import {SaleOrderService} from '../sale-order.service';
import {AbstractModalComponent, FieldForm} from '../../../core';

@Component({
  selector: 'app-order-cancel',
  templateUrl: './sale-order-cancel.component.html',
  styleUrls: ['./sale-order-cancel.component.css'],
  providers: [SaleOrderService]
})
export class SaleOrderCancelComponent extends AbstractModalComponent<SaleOrderMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      cod_fee: new FormControl(0, [Validators.required]),
      payment_type: new FormControl(null, [Validators.required]),
      banking_sms: new FormControl(null),
      note: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createNumberInput('Phí CoD *', 'cod_fee', 'Nhập kí tự'),
      FieldForm.createSelect('Thanh toán *', 'payment_type', 'Chọn một', [
        {
          text: 'CoD',
          value: 0
        },
        {
          text: 'Pay all',
          value: 1
        },
        {
          text: 'Đặt cọc',
          value: 2
        },
        {
          text: 'Khác',
          value: 3
        },
      ]),
      FieldForm.createTextArea('Giao dịch ngân hàng', 'banking_sms', 'Nhập kí tự' ,5),
      FieldForm.createTextArea('Ghi chú', 'note', 'Nhập kí tự' ,5),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: SaleOrderService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

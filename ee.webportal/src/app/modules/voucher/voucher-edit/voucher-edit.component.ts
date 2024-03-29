import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {VoucherMeta} from '../voucher.meta';
import {VoucherService} from '../voucher.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-voucher-edit',
  templateUrl: './voucher-edit.component.html',
  styleUrls: ['./voucher-edit.component.css'],
  providers: [VoucherService]
})
export class VoucherEditComponent extends AbstractModalComponent<VoucherMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null),
      code: new FormControl(null,),
      quantity: new FormControl(null,),
      min_order_value: new FormControl(null,),
      min_products_count: new FormControl(null,),
      discount_value: new FormControl(null,),
      discount_percent: new FormControl(null,),
      expired_date: new FormControl(null,),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên mã giảm *', 'name', 'Nhập tên mã giảm'),
      FieldForm.createTextInput('Mã giảm giá *', 'code', 'Nhập mã giảm giá'),
      FieldForm.createNumberInput('Số lượng *', 'quantity', 'Nhập số lượng'),
      FieldForm.createNumberInput('Giá trị tối thiểu *', 'min_order_value', 'Nhập giá trị tối thiểu'),
      FieldForm.createNumberInput('Số lượng tối thiểu *', 'min_products_count', 'Nhập số lượng tối thiểu'),
      FieldForm.createNumberInput('Tiền giảm *', 'discount_value', 'Nhập số tiền giảm'),
      FieldForm.createNumberInput('Chiết khấu *', 'discount_percent', 'Nhập chiết khấu'),
      FieldForm.createDateInput('Ngày hết hạn *', 'expired_date', 'Nhập Ngày hết hạn'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: VoucherService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

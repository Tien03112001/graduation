import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {OrderProductMeta} from '../order-product.meta';
import {OrderProductService} from '../order-product.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-order-product-edit',
  templateUrl: './order-product-edit.component.html',
  styleUrls: ['./order-product-edit.component.css'],
  providers: [OrderProductService]
})
export class OrderProductEditComponent extends AbstractModalComponent<OrderProductMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null),
      discount_value: new FormControl(null,),
      discount_percent: new FormControl(null,),
      expired_date: new FormControl(null,),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên mã giảm *', 'name', 'Nhập tên mã giảm'),
      FieldForm.createNumberInput('Tiền giảm *', 'discount_value', 'Nhập số tiền giảm'),
      FieldForm.createNumberInput('Chiết khấu *', 'discount_percent', 'Nhập chiết khấu'),
      FieldForm.createDateInput('Ngày hết hạn *', 'expired_date', 'Nhập Ngày hết hạn'),
    ];
  }

  loaded(): void {
    // this.formGroup.setValue({
    //   name: this.model.name,
    //   discount_value: this.model.discount_value,
    //   discount_percent: this.model.discount_percent,
    //   expired_date: this.model.expired_date,
    // });
  }

  constructor(
    service: OrderProductService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

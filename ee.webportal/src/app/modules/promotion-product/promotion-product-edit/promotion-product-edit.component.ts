import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PromotionProductMeta} from '../promotion-product.meta';
import {PromotionProductService} from '../promotion-product.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-promotion-product-edit',
  templateUrl: './promotion-product-edit.component.html',
  styleUrls: ['./promotion-product-edit.component.css'],
  providers: [PromotionProductService]
})
export class PromotionProductEditComponent extends AbstractModalComponent<PromotionProductMeta> {

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
    service: PromotionProductService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

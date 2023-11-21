import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {ChartMeta} from '../chart.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ChartService} from '../chart.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-chart-create',
  templateUrl: './chart-create.component.html',
  styleUrls: ['./chart-create.component.css'],
  providers: [ChartService]
})
export class ChartCreateComponent extends AbstractModalComponent<ChartMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
        name: new FormControl(null, Validators.required),
        code: new FormControl(null, Validators.required),
        quantity: new FormControl(null, Validators.required),
        min_order_value: new FormControl(null),
        min_products_count: new FormControl(null),
        discount_value: new FormControl(null),
        discount_percent: new FormControl(null),
        enable: new FormControl(0),
        expired_date: new FormControl(null, Validators.required),
      }
    );
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên mã giảm *', 'name', 'Nhập tên mã giảm'),
      FieldForm.createTextInput('Mã giảm giá *', 'code', 'Nhập mã giảm giá'),
      FieldForm.createNumberInput('Số lượng *', 'quantity', 'Nhập số lượng'),
      FieldForm.createNumberInput('Giá trị tối thiểu *', 'min_order_value', 'Nhập giá tối thiểu',),
      FieldForm.createNumberInput('Số lượng tối thiểu *', 'min_products_count', 'Nhập số lượng tối thiểu'),
      FieldForm.createNumberInput('Tiền giảm *', 'discount_value', 'Nhập số tiền giảm'),
      FieldForm.createNumberInput('Chiết khấu *', 'discount_percent', 'Nhập chiết khấu'),
      FieldForm.createCheckbox('Trạng thái *', 'enable', 'Trạng thái'),
      FieldForm.createDateInput('Ngày hết hạn *', 'expired_date', 'Nhập Ngày hết hạn'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ChartService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

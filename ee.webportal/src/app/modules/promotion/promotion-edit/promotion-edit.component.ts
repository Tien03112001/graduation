import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PromotionMeta} from '../promotion.meta';
import {PromotionService} from '../promotion.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-promotion-edit',
  templateUrl: './promotion-edit.component.html',
  styleUrls: ['./promotion-edit.component.css'],
  providers: [PromotionService]
})
export class PromotionEditComponent extends AbstractModalComponent<PromotionMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }
  loadAllType() {
    return [{
      id: 1,
      name: 'Chiết khấu theo sản phẩm',
    }, {
      id: 2,
      name: 'Đồng giá',
    }, {
      id: 3,
      name: 'FreeShip',
    }, {
      id: 4,
      name: 'Chiết khấu theo đơn hàng',
    } ];
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      type: new FormControl(null, Validators.required),
      enable: new FormControl(0),
      expired_date: new FormControl(null, Validators.required),
      published: new FormControl(false),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên*', 'name', 'Nhập tên mã giảm'),
      FieldForm.createSelect('Loại chương trình *', 'type', 'Nhập tên mã giảm', this.loadAllType()),
      FieldForm.createDateTimeInput('Thời gian hết hạn', 'expired_date', 'Nhập Ngày hết hạn'),
      FieldForm.createCheckbox('Kích hoạt', 'enable', 'Chọn'),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      name: this.model.name,
      type: this.model.type,
      enable: this.model.type,
      expired_date: this.model.expired_date,
      published: this.model.enable,


    });
    if (this.model.type == 1) {
      let fileForm1 = FieldForm.createNumberInput('Chiết khấu theo sản phẩm(%)', 'discount_percent', 'Nhập giá trị');
      this.fields.push(fileForm1);
      this.formGroup.addControl('discount_percent', new FormControl([Validators.required, Validators.min(0), Validators.max(100)]));
    }
    if (this.model.type == 2) {
      let fileForm = FieldForm.createNumberInput('Đồng giá', 'same_price', 'Nhập giá trị');
      this.formGroup.addControl('same_price', new FormControl(null, [Validators.required, Validators.min(0)]));
      this.fields.push(fileForm);
    }
    if (this.model.type == 3) {
      let fileForm = FieldForm.createNumberInput('Số lượng sản phẩm tối thiểu ', 'min_products_count', 'Nhập giá trị');
      this.fields.push(fileForm);
      this.formGroup.addControl('min_products_count', new FormControl(null,[Validators.required, Validators.pattern(/^-?(0|[1-9]\d*)?$/)]));
      let fileForm1 = FieldForm.createNumberInput('Đơn giá tối thiểu', 'min_order_value', 'Nhập giá trị');
      this.fields.push(fileForm1);
      this.formGroup.addControl('min_order_value', new FormControl(null, [Validators.required, Validators.min(0)]));

    }
    if (this.model.type == 3) {
      let fileForm = FieldForm.createNumberInput('Chiết khấu theo đơn hàng (VNĐ)', 'discount_value', 'Nhập giá trị');
      this.fields.push(fileForm);
      this.formGroup.addControl('discount_value', new FormControl(null, [Validators.required, Validators.min(0)]));
      let fileForm1 = FieldForm.createNumberInput('Đơn giá tối thiểu', 'min_order_value', 'Nhập giá trị');
      this.fields.push(fileForm1);
      this.formGroup.addControl('min_order_value', new FormControl(null, [Validators.required, Validators.min(0)]));
    }
  }

  constructor(
    service: PromotionService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }
  onFormChanged() {
    super.onFormChanged();
    this.formGroup.controls['type'].valueChanges.subscribe(value => {
      if (this.fields.length > 1) {
        this.fields.splice(4);
      }
      if (value) {
        if (value == 1) {
          this.fields.splice(4);
          if ( this.formGroup.controls['same_price']) {
            this.formGroup.removeControl('same_price');
          }
          if ( this.formGroup.controls['min_products_count']) {
            this.formGroup.removeControl('min_products_count');
          }
          if ( this.formGroup.controls['min_order_value']) {
            this.formGroup.removeControl('min_order_value');
          }
          if ( this.formGroup.controls['discount_value']) {
            this.formGroup.removeControl('discount_value');
          }
          let fileForm1 = FieldForm.createNumberInput('Chiết khấu theo sản phẩm(%)', 'discount_percent', 'Nhập giá trị');
          this.fields.push(fileForm1);
          this.formGroup.addControl('discount_percent', new FormControl(null, [Validators.required, Validators.min(0), Validators.max(100)]));
        }
        if (value == 2) {
          this.fields.splice(4);
          if ( this.formGroup.controls['discount_percent']) {
            this.formGroup.removeControl('discount_percent');
          }
          if ( this.formGroup.controls['discount_value']) {
            this.formGroup.removeControl('discount_value');
          }
          if ( this.formGroup.controls['min_order_value']) {
            this.formGroup.removeControl('min_order_value');
          }
          let fileForm = FieldForm.createNumberInput('Đồng giá', 'same_price', 'Nhập giá trị');
          this.fields.push(fileForm);
          this.formGroup.addControl('same_price', new FormControl(null, [Validators.required, Validators.min(0)]));
          let fileForm1 = FieldForm.createNumberInput('số lượng tối thiểu', 'min_products_count', 'Nhập giá trị');
          this.fields.push(fileForm1);
          this.formGroup.addControl('min_products_count', new FormControl(null, [Validators.required, Validators.min(0)]));
        }
        if (value == 3) {
          this.fields.splice(4);
          if ( this.formGroup.controls['discount_percent']) {
            this.formGroup.removeControl('discount_percent');
          }
          if ( this.formGroup.controls['discount_value']) {
            this.formGroup.removeControl('discount_value');
          }
          if ( this.formGroup.controls['same_price']) {
            this.formGroup.removeControl('same_price');
          }
          let fileForm = FieldForm.createNumberInput('Số lượng sản phẩm tối thiểu ', 'min_products_count', 'Nhập giá trị');
          this.fields.push(fileForm);
          this.formGroup.addControl('min_products_count', new FormControl(null,[Validators.required, Validators.pattern(/^-?(0|[1-9]\d*)?$/)]));
          let fileForm1 = FieldForm.createNumberInput('Đơn giá tối thiểu', 'min_order_value', 'Nhập giá trị');
          this.fields.push(fileForm1);
          this.formGroup.addControl('min_order_value', new FormControl(null, [Validators.required, Validators.min(0)]));

        }
        if (value == 4) {
          this.fields.splice(4);
          if ( this.formGroup.controls['discount_percent']) {
            this.formGroup.removeControl('discount_percent');
          }
          if ( this.formGroup.controls['min_products_count']) {
            this.formGroup.removeControl('min_products_count');
          }
          if ( this.formGroup.controls['same_price']) {
            this.formGroup.removeControl('min_order_value');
          }
          let fileForm = FieldForm.createNumberInput('Chiết khấu theo đơn hàng (VNĐ)', 'discount_value', 'Nhập giá trị');
          this.fields.push(fileForm);
          this.formGroup.addControl('discount_value', new FormControl(null, [Validators.required, Validators.min(0)]));
          let fileForm1 = FieldForm.createNumberInput('Đơn giá tối thiểu', 'min_order_value', 'Nhập giá trị');
          this.fields.push(fileForm1);
          this.formGroup.addControl('min_order_value', new FormControl(null, [Validators.required, Validators.min(0)]));
        }
      } else {
        this.fields.splice(4);
      }
    });
  }

}

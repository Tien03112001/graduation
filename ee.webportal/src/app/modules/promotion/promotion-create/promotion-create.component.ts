import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {PromotionMeta} from '../promotion.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PromotionService} from '../promotion.service';
import {FieldForm} from '../../../core/common';
import {ProductService} from '../../product/product.service';

@Component({
  selector: 'app-promotion-create',
  templateUrl: './promotion-create.component.html',
  styleUrls: ['./promotion-create.component.css'],
  providers: [PromotionService, ProductService]
})
export class PromotionCreateComponent extends AbstractModalComponent<PromotionMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };
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
  onInit(): void {
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
      // discount_percent: new FormControl(null ),
      // same_price: new FormControl(null ),
      // min_products_count: new FormControl(null ),
      // min_order_value: new FormControl(null ),
      // discount_value: new FormControl(null ),
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
  }
  onFormChanged() {
    super.onFormChanged();
    this.formGroup.controls['type'].valueChanges.subscribe(value => {
            if (this.fields.length > 1) {
              this.fields.splice(4);
            }
            if (value) {
              if (value == 1) {
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
                  this.formGroup.addControl('discount_percent', new FormControl(null, [Validators.required, Validators.min(1), Validators.max(100)]));
              }
              if (value == 2) {
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
                this.formGroup.addControl('discount_value', new FormControl(null, [Validators.required, Validators.min(1)]));
                let fileForm1 = FieldForm.createNumberInput('Đơn giá tối thiểu', 'min_order_value', 'Nhập giá trị');
                this.fields.push(fileForm1);
                this.formGroup.addControl('min_order_value', new FormControl(null, [Validators.required, Validators.min(1)]));
              }
            } else {
              this.fields.splice(4);
            }


    });
  }

  constructor(
    service: PromotionService,
    modal: BsModalRef,
    builder: FormBuilder,
    private productService: ProductService
  ) {
    super(service, modal, builder);
  }

}

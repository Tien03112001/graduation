import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import { AbstractModalComponent, FieldForm, ObjectUtil } from '../../../core';
import { ProductMeta } from '../../product/product.meta';
import {InventoryProductService} from '../inventory-product.service';

@Component({
  selector: 'app-inventory-product-create',
  templateUrl: './inventory-product-create.component.html',
  styleUrls: ['./inventory-product-create.component.css'],
  providers: [InventoryProductService]
})
export class InventoryProductCreateComponent extends AbstractModalComponent<any> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      variant: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('^(?=.*[a-zA-Z0-9\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-zA-Z0-9\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$')]),
      quantity: new FormControl(null, [Validators.required, Validators.min(0), Validators.pattern('^(?=.*[0-9]+)[0-9]*$'), Validators.max(9999999999)]),
      product_id: new FormControl(1),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên biến thể *', 'variant', 'Nhập kí tự'),
      FieldForm.createNumberInput('Số lượng *', 'quantity', 'Nhập số'),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      variant: null,
      quantity: null,
      product_id: this.model.product_id,
    });
  }

  constructor(
    service: InventoryProductService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import { AbstractModalComponent, FieldForm } from '../../../core';
import {InventoryProductMeta} from '../inventory-product.meta';
import {InventoryProductService} from '../inventory-product.service';

@Component({
  selector: 'app-inventory-product-edit',
  templateUrl: './inventory-product-edit.component.html',
  styleUrls: ['./inventory-product-edit.component.css'],
  providers: [InventoryProductService]
})
export class InventoryProductEditComponent extends AbstractModalComponent<InventoryProductMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      quantity: new FormControl(1, [Validators.required, Validators.min(0), Validators.pattern('^(?=.*[0-9]+)[0-9]*$'), Validators.max(9999999999)]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createNumberInput('Số lượng thực tế', 'quantity', '0'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: InventoryProductService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import { AbstractModalComponent, FieldForm } from '../../../core';
import {ProductVariantMeta} from '../product-variant.meta';
import {ProductVariantService} from '../product-variant.service';

@Component({
  selector: 'app-product-variant-edit',
  templateUrl: './product-variant-edit.component.html',
  styleUrls: ['./product-variant-edit.component.css'],
  providers: [ProductVariantService]
})
export class ProductVariantEditComponent extends AbstractModalComponent<ProductVariantMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      weight: new FormControl(null, [Validators.required, Validators.min(0), Validators.max(9999999999)]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createNumberInput('Khối lượng (kg)', 'weight', 'Nhập khối lượng'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ProductVariantService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

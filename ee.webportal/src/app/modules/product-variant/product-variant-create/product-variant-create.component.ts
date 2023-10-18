import { Component } from '@angular/core';
import { AbstractModalComponent } from '../../../core/crud';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { ProductVariantMeta } from '../product-variant.meta';
import { ProductVariantService } from '../product-variant.service';
import { FieldForm } from '../../../core/common';

@Component({
  selector: 'app-product-variant-create',
  templateUrl: './product-variant-create.component.html',
  styleUrls: ['./product-variant-create.component.css'],
  providers: [ProductVariantService]
})
export class ProductVariantCreateComponent extends AbstractModalComponent<ProductVariantMeta> {
  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255)]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên biến thể', 'name', 'Nhập tên')
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

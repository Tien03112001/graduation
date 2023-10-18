import { Component } from '@angular/core';
import { AbstractModalComponent } from '../../../core/crud';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { ProductVariantMeta } from '../product-variant.meta';
import { ProductVariantService } from '../product-variant.service';
import { FieldForm } from '../../../core/common';

@Component({
  selector: 'app-product-variant-edit',
  templateUrl: './product-variant-edit.component.html',
  styleUrls: ['./product-variant-edit.component.css'],
  providers: [ProductVariantService]
})
export class ProductVariantEditComponent extends AbstractModalComponent<ProductVariantMeta> {
  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }


  loaded(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
    ];
  }

  constructor(
    service: ProductVariantService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

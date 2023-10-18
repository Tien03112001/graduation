import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {Product_categoryMeta} from '../product_category.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {Product_categoryService} from '../product_category.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-product_category-create',
  templateUrl: './product_category-create.component.html',
  styleUrls: ['./product_category-create.component.css'],
  providers: [Product_categoryService]
})
export class Product_categoryCreateComponent extends AbstractModalComponent<Product_categoryMeta> {


  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      category: new FormControl(null),
      name: new FormControl(null, Validators.required),
      published: new FormControl(true, Validators.required),
      slug: new FormControl(null, Validators.required),
      parent_id: new FormControl('0'),
      summary: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập ký tự'),
      FieldForm.createCheckbox('Kích hoạt', 'published', 'chọn'),
      FieldForm.createTextInput('Slug', 'slug', 'Nhập ký tự'),
      FieldForm.createHtmlInput('Tóm tắt', 'summary', 'Nhập ký tự'),
    ];
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }

  loaded(): void {
  }

  constructor(
    service: Product_categoryService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

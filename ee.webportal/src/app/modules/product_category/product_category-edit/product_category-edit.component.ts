import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {Product_categoryMeta} from '../product_category.meta';
import {Product_categoryService} from '../product_category.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-product_category-edit',
  templateUrl: './product_category-edit.component.html',
  styleUrls: ['./product_category-edit.component.css'],
  providers: [Product_categoryService]
})
export class Product_categoryEditComponent extends AbstractModalComponent<Product_categoryMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };

  onParentChange(e) {
    if (e) {
      this.formGroup.controls['parent_id'].setValue(e.id);
    } else {
      this.formGroup.controls['parent_id'].setValue(0);
    }
  }

  loadAllCategories() {
    return this.service.loadAll();
  }

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
      parent_id: new FormControl(0),
      summary: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect2('Danh mục *', 'category', 'Chọn danh mục', 'loadAllCategories'),
      FieldForm.createCheckbox('Kích hoạt', 'published', 'chọn'),
      FieldForm.createTextInput('Tên', 'name', 'Nhập ký tự'),
      FieldForm.createTextInput('Slug', 'slug', 'Nhập ký tự'),
      FieldForm.createHtmlInput('Tóm tắt', 'summary', 'Nhập ký tự'),
    ];
  }


  loaded(): void {

  }


  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }

  constructor(
    service: Product_categoryService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }
  onFormChanged() {
    this.formGroup.controls['category'].valueChanges.subscribe((value => {
      if (value && value.length > 0) {
        this.formGroup.controls['parent_id'].setValue(value[0].id);
      } else {
        this.formGroup.controls['parent_id'].setValue(null);
      }
    }));
  }
}

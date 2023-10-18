import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {Product_categoryService} from '../../product_category/product_category.service';
import {Product_categoryMeta} from '../../product_category/product_category.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-product-category-create',
  templateUrl: './product-create-category.component.html',
  styleUrls: ['./product-create-category.component.css'],
  providers: [Product_categoryService]
})
export class ProductCreateCategoryComponent extends AbstractModalComponent<Product_categoryMeta> {
  img: any = null;
  categories: Product_categoryMeta[] = [];
  ckEditorConfig: any = {
    height: '250px'
  };
  selectSetting = {
    text: 'Chọn nhãn',
    labelKey: 'name',
    searchBy: ['name'],
    enableSearchFilter: true,
    singleSelection: false,
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required,Validators.maxLength(255),Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s].*$')]),
      published: new FormControl(false),
      slug: new FormControl(null, [Validators.required,Validators.maxLength(255),Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s].*$')]),
      parent_id: new FormControl(0),
      summary: new FormControl(null),
      category: new FormControl(null, Validators.required)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect2('Danh mục cha*', 'category', 'Chọn danh mục', 'loadAllCategories'),
      FieldForm.createCheckbox('Kích hoạt', 'published', 'Chọn'),
      FieldForm.createTextInput('Tên*', 'name', 'Nhập tên'),
      FieldForm.createTextInput('Đường dẫn*', 'slug', 'Nhập đường dẫn'),
      FieldForm.createHtmlInput('Tóm tắt', 'summary', 'Nhập tóm tắt'),
    ];
  }

  loadAllCategories() {
    return this.service.loadAll();
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
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
    this.loadAllCategories();
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

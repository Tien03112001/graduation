import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef, BsModalService} from 'ngx-bootstrap';
import {ProductMeta} from '../product.meta';
import {ProductService} from '../product.service';
import {AbstractModalComponent} from '../../../core/crud';
import {ProductCreateCategoryComponent} from '../product-create-category/product-create-category.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {Product_categoryMeta} from '../../product_category/product_category.meta';
import {Product_categoryService} from '../../product_category/product_category.service';

@Component({
  selector: 'app-product-edit',
  templateUrl: './product-edit.component.html',
  styleUrls: ['./product-edit.component.css'],
  providers: [ProductService, Product_categoryService]
})
export class ProductEditComponent extends AbstractModalComponent<ProductMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };


  loadAllCategories() {
    return this.categoryService.loadAll();
  }


  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required,Validators.maxLength(255),Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s].*$')]),
      code: new FormControl(null, [Validators.required,Validators.maxLength(255),Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s].*$')]),
      category_id: new FormControl(null),
      category_slug: new FormControl(null),
      summary: new FormControl(null),
      image: new FormControl(null),
      price: new FormControl(null, [Validators.required,Validators.min(0),Validators.maxLength(255)]),
      published: new FormControl(true),
      category: new FormControl(null, Validators.required),
      slug: new FormControl(null, [Validators.required,Validators.maxLength(255),Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s].*$')])
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect3('Danh mục *', 'category', 'Chọn danh mục', 'loadAllCategories'),
      FieldForm.createTextInput('Tên sản phẩm *', 'name', 'Nhập tên sản phẩm'),
      FieldForm.createCheckbox('Kích hoạt', 'published', 'Chọn'),
      FieldForm.createTextInput('Đường dẫn *', 'slug', 'Nhập đường dẫn'),
      FieldForm.createTextInput('Code *', 'code', 'Nhập mã code sản phẩm'),
      FieldForm.createFileInput('Ảnh đại diện *', 'image', 'Chọn ảnh', this.onImageUploadChange),
      FieldForm.createNumberInput('Giá sản phẩm *', 'price', 'Nhập giá sản phẩm'),
      FieldForm.createHtmlInput('Tóm tắt', 'summary', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ProductService,
    modal: BsModalRef,
    builder: FormBuilder,
    private categoryService: Product_categoryService,
    private modalService: BsModalService,
  ) {
    super(service, modal, builder);
  }

  createCategory() {
    let modalRef = this.modalService.show(ProductCreateCategoryComponent, {
      ignoreBackdropClick: true,
      class: 'modal-lg'
    });
    let modal: AbstractModalComponent<Product_categoryMeta> = <AbstractModalComponent<Product_categoryMeta>>modalRef.content;
    let model = new Product_categoryMeta();
    modal.setModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<Product_categoryMeta>) => {
      if (result.success) {
        this.fields[0].data.push(result.data);
      }
      sub.unsubscribe();
    });
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }
}

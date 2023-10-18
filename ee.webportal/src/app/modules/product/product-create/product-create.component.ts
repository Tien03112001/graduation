import {Component} from '@angular/core';
import {ProductMeta} from '../product.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef, BsModalService} from 'ngx-bootstrap';
import {ProductService} from '../product.service';
import {AbstractModalComponent} from '../../../core/crud';
import {ProductCreateCategoryComponent} from '../product-create-category/product-create-category.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {Product_categoryMeta} from '../../product_category/product_category.meta';
import {Product_categoryService} from '../../product_category/product_category.service';

@Component({
  selector: 'app-product-create',
  templateUrl: './product-create.component.html',
  styleUrls: ['./product-create.component.css'],
  providers: [ProductService, Product_categoryService, BsModalService]
})
export class ProductCreateComponent extends AbstractModalComponent<ProductMeta> {
  img: any = null;
  ckEditorConfig: any = {
    height: '350px'
  };

  loadAllCategories() {
    return this.categoryService.loadAll();
  }

  onCategoryChange(e: any) {
    if (e) {
      this.formGroup.controls['category_id'].setValue(e.id);
      this.formGroup.controls['category_slug'].setValue(e.slug);
    }
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
      price: new FormControl(null, [Validators.required,Validators.min(0),Validators.maxLength(20)]),
      published: new FormControl(false),
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

  createObjWithImage() {
    if (this.formGroup.controls['published'].value) {
      this.formGroup.controls['published'].setValue(1);
    } else {
      this.formGroup.controls['published'].setValue(0);
    }
    // this.formGroup.controls['label'].setValue(JSON.stringify(this.formGroup.controls['labels'].value));
    if (this.formGroup.controls['category'].value) {
      this.formGroup.controls['category_id'].setValue(this.formGroup.controls['category'].value[0].id);
      this.formGroup.controls['category_slug'].setValue(this.formGroup.controls['category'].value[0].slug);
    }
    this.createWithImage();
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
    console.log(input.files[0]);
  }
}

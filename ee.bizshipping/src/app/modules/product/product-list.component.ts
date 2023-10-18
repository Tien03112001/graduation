import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {ProductService} from './product.service';
import {ProductMeta} from './product.meta';
import {ProductCreateComponent} from './product-create/product-create.component';
import { AbstractCRUDComponent, FieldForm, TitleService } from '../../core';

@Component({
  selector: 'app-product-list',
  templateUrl: './product-list.component.html',
  styleUrls: ['./product-list.component.css'],
  providers: [ProductService],
})
export class ProductListComponent extends AbstractCRUDComponent<ProductMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Tồn kho';
  }

  getCreateModalComponent(): any {
    return ProductCreateComponent;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', backdrop: 'static', keyboard: false};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {backdrop: 'static', keyboard: false};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.maxLength(255)),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên sản phẩm', 'search', 'Nhập từ khóa','col-md-6'),
    ];
  }

  initNewModel(): ProductMeta {
    return new ProductMeta();
  }

  constructor(
    service: ProductService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  public goToPageNumber() {
    this.nextPage = Math.round(this.nextPage);
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (Math.round(this.nextPage) > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }

}

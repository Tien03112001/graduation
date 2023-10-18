import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../core/crud';
import {FacebookProductCategoryService} from './facebook-product-category.service';
import {FacebookProductCategoryMeta} from './facebook-product-category.meta';
import {FacebookProductCategoryImportComponent} from './facebook-product-category-import/facebook-product-category-import.component';
import { FieldForm, ModalResult } from '../../core/common';
import { TitleService } from '../../core/services';

@Component({
  selector: 'app-facebook-product-category-list',
  templateUrl: './facebook-product-category-list.component.html',
  styleUrls: ['./facebook-product-category-list.component.css'],
  providers: [FacebookProductCategoryService],
})
export class FacebookProductCategoryListComponent extends AbstractCRUDComponent<FacebookProductCategoryMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Danh mục sản phẩm';
  }

  getCreateModalComponent(): any {
    return null;
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
      FieldForm.createTextInput('Tìm kiếm theo tên danh mục', 'search', 'Nhập từ khóa','col-md-6'),
    ];
  }

  initNewModel(): FacebookProductCategoryMeta {
    return new FacebookProductCategoryMeta();
  }

  constructor(
    service: FacebookProductCategoryService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  import() {
    const config = {ignoreBackdropClick: true};
    const modalRef = this.modalService.show(FacebookProductCategoryImportComponent, config);
    let modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
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

  truncate() {
    (<FacebookProductCategoryService>this.service).truncate('').subscribe(res => {
      this.service.toastSuccessfully('Xóa');
      this.load();
    }, () => this.service.toastFailedEdited());
  }
}

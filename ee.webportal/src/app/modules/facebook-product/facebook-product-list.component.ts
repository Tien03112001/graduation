import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {FacebookProductMeta} from './facebook-product.meta';
import { FacebookProductEditComponent } from './facebook-product-edit/facebook-product-edit.component';
import { AbstractCRUDComponent } from '../../core/crud';
import { FieldForm } from '../../core/common';
import { TitleService } from '../../core/services';
import { FacebookProductService } from './facebook-product.service';

@Component({
  selector: 'app-facebook-product-list',
  templateUrl: './facebook-product-list.component.html',
  styleUrls: ['./facebook-product-list.component.css'],
  providers: [FacebookProductService],
})
export class FacebookProductListComponent extends AbstractCRUDComponent<FacebookProductMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Sản phẩm';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return FacebookProductEditComponent;
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
      FieldForm.createTextInput('Tìm kiếm theo mã sản phẩm', 'search', 'Nhập từ khóa','col-md-6'),
    ];
  }

  initNewModel(): FacebookProductMeta {
    return new FacebookProductMeta();
  }

  constructor(
    service: FacebookProductService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  create() {
    let item: FacebookProductMeta;
    this.service.store(item).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.load();
    }, () => this.service.toastFailedCreated());
  }

  onEnableOn(item: FacebookProductMeta, index: number) {
    this.service.enable(item.id).subscribe((res: FacebookProductMeta) => {
      this.service.toastSuccessfully('Bật');
      this.load();
    }, () => this.service.toastFailed('Bật'));
  }

  onEnableOff(item: FacebookProductMeta, index: number) {
    this.service.disable(item.id).subscribe((res: FacebookProductMeta) => {
      this.list[index].enable = res.enable;
      this.service.toastSuccessfully('Tắt');
      this.load();
    }, () => this.service.toastFailed('Tắt'));
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

  createXML() {
    (<FacebookProductService>this.service).createXML().subscribe(res => {
      this.service.toastSuccessfully('Xuất file');
      this.load();
    }, () => this.service.toastFailed('Xuất file'));
  }
}

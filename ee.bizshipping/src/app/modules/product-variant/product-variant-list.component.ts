import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {ProductVariantService} from './product-variant.service';
import {ProductVariantMeta} from './product-variant.meta';
import { AbstractCRUDComponent, AbstractModalComponent, FieldForm, ModalResult, ObjectUtil, TitleService } from '../../core';
import { ProductVariantEditComponent } from './product-variant-edit/product-variant-edit.component';

@Component({
  selector: 'app-product-variant-list',
  templateUrl: './product-variant-list.component.html',
  styleUrls: ['./product-variant-list.component.css'],
  providers: [ProductVariantService],
})
export class ProductVariantListComponent extends AbstractCRUDComponent<ProductVariantMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Cấu hình sản phẩm';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return ProductVariantEditComponent;
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

  initNewModel(): ProductVariantMeta {
    return new ProductVariantMeta();
  }

  constructor(
    service: ProductVariantService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  public editProductVariant(item: ProductVariantMeta, index1: number, variant: ProductVariantMeta, index2: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(ProductVariantEditComponent, config);
    let modal: AbstractModalComponent<ProductVariantMeta> = <AbstractModalComponent<ProductVariantMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(variant));
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductVariantMeta>) => {
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

}

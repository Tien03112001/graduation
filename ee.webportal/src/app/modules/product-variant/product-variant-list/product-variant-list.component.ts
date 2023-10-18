import { Component } from '@angular/core';
import { AbstractCRUDModalComponent, AbstractModalComponent } from '../../../core/crud';
import { PhotoMeta } from '../../photo-CRUD/photo.meta';
import { FormBuilder, FormGroup } from '@angular/forms';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap';
import { ModalResult } from '../../../core/common';
import { ProductVariantMeta } from '../product-variant.meta';
import { ProductVariantService } from '../product-variant.service';
import { ProductVariantCreateComponent } from '../product-variant-create/product-variant-create.component';
import { ProductVariantEditComponent } from '../product-variant-edit/product-variant-edit.component';
import { ObjectUtil } from '../../../core/utils';

@Component({
  selector: 'app-product-variant',
  templateUrl: './product-variant-list.component.html',
  styleUrls: ['./product-variant-list.component.css'],
  providers: [ProductVariantService]
})
export class ProductVariantListComponent extends AbstractCRUDModalComponent<ProductVariantMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }


  loaded(): void {
    this.load();
  }

  getTitle(): string {
    return "";
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  getCreateModalComponent(): any {
    return ProductVariantCreateComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return { class: 'modal-lg', ignoreBackdropClick: true };
  }

  getEditModalComponent(): any {
    return ProductVariantEditComponent;
  }

  getEditModalComponentOptions(): ModalOptions {

    return { class: 'modal-lg', ignoreBackdropClick: true };
  }

  initNewModel(): ProductVariantMeta {
    let newModel: ProductVariantMeta = new ProductVariantMeta();
    newModel.product_id = this.relatedModel.product_id;
    return newModel;
  }

  constructor(
    service: ProductVariantService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modalRef, modal, builder);
  }

  public load() {
    let param = {
      product_id: this.relatedModel.product_id,
    };
    this.service.loadByParams(param).subscribe((res: ProductVariantMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }

  public createSize() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<ProductVariantMeta> = <AbstractModalComponent<ProductVariantMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductVariantMeta>) => {
      this.load();
    });
  }

  dismiss() {
    this.onHidden.emit(new ModalResult<PhotoMeta[]>(this.list));
    this.modal.hide();
  }

}

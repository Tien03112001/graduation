import {Component} from '@angular/core';
import {AbstractCRUDModalComponent} from '../../../core/crud';
import {PhotoMeta} from '../../photo-CRUD/photo.meta';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {ModalResult} from '../../../core/common';
import {RelatedProductMeta} from '../related-product.meta';
import {RelatedProductService} from '../related-product.service';
import {RelatedProductCreateComponent} from '../related-product-create/related-product-create.component';


@Component({
  selector: 'app-related-product',
  templateUrl: './related-product-list.component.html',
  styleUrls: ['./related-product-list.component.css'],
  providers: [RelatedProductService]
})
export class RelatedProductListComponent extends AbstractCRUDModalComponent<RelatedProductMeta> {

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  getCreateModalComponent(): any {
    return RelatedProductCreateComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  public getEditModalComponent() {
    throw new Error('Method not implemented.');
  }

  getEditModalComponentOptions(): ModalOptions {

    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  initNewModel(): RelatedProductMeta {
    let newModel: RelatedProductMeta = new RelatedProductMeta();
    newModel.product_id = this.relatedModel.product_id;
    newModel.related_id = this.relatedModel.related_id;
    newModel.existsRelations = this.list;
    return newModel;
  }

  onInit(): void {
  }

  onDestroy(): void {
  }


  loaded(): void {
    this.load();
  }

  constructor(
    service: RelatedProductService,
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
    this.service.loadByParams(param).subscribe((res: RelatedProductMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }

  dismiss() {
    this.onHidden.emit(new ModalResult<PhotoMeta[]>(this.list));
    this.modal.hide();
  }

  getTitle(): string {
    return "";
  }

}

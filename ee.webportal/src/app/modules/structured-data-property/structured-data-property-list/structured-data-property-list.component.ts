import { Component } from '@angular/core';
import { AbstractCRUDModalComponent } from '../../../core/crud';
import { PhotoMeta } from '../../photo-CRUD/photo.meta';
import { FormBuilder, FormGroup } from '@angular/forms';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap';
import { ModalResult } from '../../../core/common';
import { StructuredDataPropertyMeta } from '../structured-data-property.meta';
import { StructuredDataPropertyService } from '../structured-data-property.service';
import { StructuredDataPropertyCreateComponent } from '../structured-data-property-create/structured-data-property-create.component';
import { StructuredDataPropertyEditComponent } from '../structured-data-property-edit/structured-data-property-edit.component';

@Component({
  selector: 'app-structured-data-property',
  templateUrl: './structured-data-property-list.component.html',
  styleUrls: ['./structured-data-property-list.component.css'],
  providers: [StructuredDataPropertyService]
})
export class StructuredDataPropertyListComponent extends AbstractCRUDModalComponent<StructuredDataPropertyMeta> {

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  getCreateModalComponent(): any {
    return StructuredDataPropertyCreateComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return { class: 'modal-lg', ignoreBackdropClick: true };
  }

  getEditModalComponent(): any {
    return StructuredDataPropertyEditComponent;
  }

  getEditModalComponentOptions(): ModalOptions {

    return { class: 'modal-lg', ignoreBackdropClick: true };
  }

  initNewModel(): StructuredDataPropertyMeta {
    let newModel: StructuredDataPropertyMeta = new StructuredDataPropertyMeta();
    newModel.type_id = this.relatedModel.type_id;
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
    service: StructuredDataPropertyService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modalRef, modal, builder);
  }

  public load() {
    let param = {
      type_id: this.relatedModel.type_id,
    };
    this.service.loadByParams(param).subscribe((res: StructuredDataPropertyMeta[]) => {
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

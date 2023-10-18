import {Component} from '@angular/core';
import {AbstractCRUDModalComponent} from '../../../core/crud';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {ModalResult} from '../../../core/common';
import {StructuredDataMeta} from '../structured-data.meta';
import {StructuredDataService} from '../structured-data.service';
import {StructuredDataCreateComponent} from '../structured-data-create/structured-data-create.component';
import {StructuredDataEditComponent} from '../structured-data-edit/structured-data-edit.component';

@Component({
  selector: 'app-structured-data',
  templateUrl: './structured-data-list.component.html',
  styleUrls: ['./structured-data-list.component.css'],
  providers: [StructuredDataService]
})
export class StructuredDataListComponent extends AbstractCRUDModalComponent<StructuredDataMeta> {

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  getCreateModalComponent(): any {
    return StructuredDataCreateComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponent(): any {
    return StructuredDataEditComponent;
  }

  getEditModalComponentOptions(): ModalOptions {

    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  initNewModel(): StructuredDataMeta {
    let newModel: StructuredDataMeta = new StructuredDataMeta();
    newModel.structureble_id = this.relatedModel.structureble_id;
    newModel.structureble_type = this.relatedModel.structureble_type;
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
    service: StructuredDataService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modalRef, modal, builder);
  }

  public load() {
    let param = {
      structureble_id: this.relatedModel.structureble_id,
      structureble_type: this.relatedModel.structureble_type
    };
    this.service.loadByParams(param).subscribe((res: StructuredDataMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }

  dismiss() {
    this.onHidden.emit(new ModalResult<StructuredDataMeta[]>(this.list));
    this.modal.hide();
  }

  getTitle(): string {
    return "";
  }

}

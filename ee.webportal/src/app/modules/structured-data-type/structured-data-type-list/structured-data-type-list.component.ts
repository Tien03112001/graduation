import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {StructuredDataTypeMeta} from '../structured-data-type.meta';
import {StructuredDataTypeService} from '../structured-data-type.service';
import {StructuredDataTypeCreateComponent} from '../structured-data-type-create/structured-data-type-create.component';
import {StructuredDataTypeEditComponent} from '../structured-data-type-edit/structured-data-type-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {ModalResult} from '../../../core/common';
import {StructuredDataPropertyMeta} from '../../structured-data-property/structured-data-property.meta';
import {
  StructuredDataPropertyListComponent
} from '../../structured-data-property/structured-data-property-list/structured-data-property-list.component';

@Component({
  selector: 'app-structured-data-type',
  templateUrl: './structured-data-type-list.component.html',
  styleUrls: ['./structured-data-type-list.component.css'],
  providers: [StructuredDataTypeService]
})
export class StructuredDataTypeListComponent extends AbstractCRUDComponent<StructuredDataTypeMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Structured data types';
  }

  getCreateModalComponent(): any {
    return StructuredDataTypeCreateComponent;
  }

  getEditModalComponent(): any {
    return StructuredDataTypeEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): StructuredDataTypeMeta {
    return new StructuredDataTypeMeta();
  }

  showProperty(item: StructuredDataPropertyMeta, index: number) {
    let modalRef = this.modalService.show(StructuredDataPropertyListComponent, {
      ignoreBackdropClick: true,
      'class': 'modal-huge'
    });
    let modal: AbstractCRUDModalComponent<MetaDataMeta> = <AbstractCRUDModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new StructuredDataPropertyMeta();
    metaData.type_id = item.id;
    modal.setRelatedModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<StructuredDataPropertyMeta[]>) => {
      if (result.success) {
        this.list[index].structured_properties = result.data;
      }
      sub.unsubscribe();
    });
  }

  constructor(
    service: StructuredDataTypeService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

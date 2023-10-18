import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {FormMeta} from '../form.meta';
import {FormService} from '../form.service';
import {FormCreateComponent} from '../form-create/form-create.component';
import {FormEditComponent} from '../form-edit/form-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {ModalResult} from '../../../core/common';
import {FormAttributeCreateComponent} from '../../form-attribute/form-attribute-create/form-attribute-create.component';
import {FormAttributeMeta} from '../../form-attribute/form-attribute.meta';

@Component({
  selector: 'app-form',
  templateUrl: './form-list.component.html',
  styleUrls: ['./form-list.component.css'],
  providers: [FormService]
})
export class FormListComponent extends AbstractCRUDComponent<FormMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý form';
  }

  getCreateModalComponent(): any {
    return FormCreateComponent;
  }

  getEditModalComponent(): any {
    return FormEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      approved: new FormControl('')
    });
  }

  initNewModel(): FormMeta {
    return new FormMeta();
  }

  createAttribute(item: FormMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(FormAttributeCreateComponent, config);
    const modal: AbstractModalComponent<FormAttributeMeta> = <AbstractModalComponent<FormAttributeMeta>>modalRef.content;
    const dynamicTable = new FormAttributeMeta();
    dynamicTable.form_id = item.id;
    modal.setModel(dynamicTable);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        let itemCreated: FormAttributeMeta = result.data;
        item.attributes.push(itemCreated);
      }
      sub.unsubscribe();
    });
  }

  constructor(
    service: FormService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }
}

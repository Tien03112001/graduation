import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {AttributeMeta} from '../attribute.meta';
import {AttributeService} from '../attribute.service';
import {AttributeCreateComponent} from '../attribute-create/attribute-create.component';
import {AttributeEditComponent} from '../attribute-edit/attribute-edit.component';
import {AttributeOptionListComponent} from '../../attribute-option/attribute-option-list/attribute-option-list.component';
import {AttributeOptionMeta} from '../../attribute-option/attribute-option.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-attribute-list',
  templateUrl: './attribute-list.component.html',
  styleUrls: ['./attribute-list.component.css'],
  providers: [AttributeService]
})
export class AttributeComponent extends AbstractCRUDComponent<AttributeMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý thuộc tính sản phẩm';
  }

  getCreateModalComponent(): any {
    return AttributeCreateComponent;
  }

  getEditModalComponent(): any {
    return AttributeEditComponent;
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
    });
  }

  public initSearchForm(): FieldForm[] {
    return [
      {
        label: 'Theo tên, mô tả',
        type: 'input',
        typeof: 'text',
        formControl: 'search',
        placeHolder: 'Từ khóa',
      },
      {
        label: 'Theo trạng thái',
        type: 'select',
        typeof: 'text',
        formControl: 'search',
        placeHolder: 'Từ khóa',
        data: [
          {
            id: 0,
            name: 'Không hoạt động'
          },
          {
            id: 1,
            name: 'Hoạt động'
          }
        ]
      },
    ];
  }

  initNewModel(): AttributeMeta {
    return new AttributeMeta();
  }

  option(item: AttributeMeta, index: number) {
    let modalRef = this.modalService.show(AttributeOptionListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<AttributeOptionMeta> = <AbstractCRUDModalComponent<AttributeOptionMeta>>modalRef.content;
    modal.setRelatedModel(item);
  }

  constructor(
    service: AttributeService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

import {Component} from '@angular/core';
import {AbstractCRUDModalComponent} from '../../../core/crud';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {AttributeTypeMeta} from '../attribute-type.meta';
import {AttributeTypeService} from '../attribute-type.service';
import {AttributeTypeCreateComponent} from '../attribute-type-create/attribute-type-create.component';
import {AttributeTypeEditComponent} from '../attribute-type-edit/attribute-type-edit.component';

@Component({
  selector: 'app-attribute-type',
  templateUrl: './attribute-type-list.component.html',
  styleUrls: ['./attribute-type-list.component.css'],
  providers: [AttributeTypeService]
})
export class AttributeTypeListComponent extends AbstractCRUDModalComponent<AttributeTypeMeta> {
  category: string = '';

  onInit(): void {
  }

  onDestroy(): void {
  }

  getCreateModalComponent(): any {
    return AttributeTypeCreateComponent;
  }

  getEditModalComponent(): any {
    return AttributeTypeEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-small'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-small'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      product_category_id: new FormControl(null, Validators.required)
    });
  }

  initNewModel(): AttributeTypeMeta {
    let model = new AttributeTypeMeta();
    model.product_category_id = this.relatedModel.id;
    return model;
  }

  constructor(
    service: AttributeTypeService,
    modal: BsModalRef,
    modalService: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {

    super(service, modal, modalService, builder);
  }

  loaded(): void {
    this.searchForm.controls['product_category_id'].setValue(this.relatedModel.id);
    this.category = this.relatedModel.name;
    this.load();
  }

  getTitle(): string {
    return "";
  }

}

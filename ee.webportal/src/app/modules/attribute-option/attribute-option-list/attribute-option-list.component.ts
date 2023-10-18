import {Component} from '@angular/core';
import {AbstractCRUDModalComponent} from '../../../core/crud';
import {AppPagination} from '../../../core/common';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ObjectUtil} from '../../../core/utils';
import {AttributeOptionMeta} from '../attribute-option.meta';
import {AttributeOptionService} from '../attribute-option.service';
import {AttributeOptionCreateComponent} from '../attribute-option-create/attribute-option-create.component';
import {AttributeOptionEditComponent} from '../attribute-option-edit/attribute-option-edit.component';

@Component({
  selector: 'app-attribute-option-list',
  templateUrl: './attribute-option-list.component.html',
  styleUrls: ['./attribute-option-list.component.css'],
  providers: [AttributeOptionService]
})
export class AttributeOptionListComponent extends AbstractCRUDModalComponent<AttributeOptionMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  getCreateModalComponent(): any {
    return AttributeOptionCreateComponent;
  }

  getEditModalComponent(): any {
    return AttributeOptionEditComponent;
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
      article_id: new FormControl(null, Validators.required)
    });
  }

  initNewModel(): AttributeOptionMeta {
    let model = new AttributeOptionMeta();
    model.content_attribute_id = this.relatedModel.id;
    return model;
  }

  public remove(item: AttributeOptionMeta, index: number) {
    this.optionService.delete(item['id']).subscribe(() => {
      this.list.splice(index, 1);
      this.service.toastSuccessfullyDeleted();
    }, err => this.service.toastFailedDeleted());

  }

  constructor(
    service: AttributeOptionService,
    modal: BsModalRef,
    modalService: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private optionService: AttributeOptionService
  ) {

    super(service, modal, modalService, builder);
  }

  loaded(): void {
    this.service.setURLRestAPI(`attributes/${this.relatedModel.id}/option`);
    let params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, ObjectUtil.ignoreNullValue(this.searchForm.value));
    this.service.loadByPage(params).subscribe((data: any) => {
        this.list = data.data;
        this.pagination.set(data);
      }, err => {
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
      }
    );
  }

  getTitle(): string {
    return "";
  }
}

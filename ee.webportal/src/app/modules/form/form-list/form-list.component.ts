import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {FormMeta} from '../form.meta';
import {FormService} from '../form.service';
import {FormCreateComponent} from '../form-create/form-create.component';
import {FormEditComponent} from '../form-edit/form-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {AppPagination, ModalResult} from '../../../core/common';
import {FormAttributeCreateComponent} from '../../form-attribute/form-attribute-create/form-attribute-create.component';
import {FormAttributeMeta} from '../../form-attribute/form-attribute.meta';
import {FormAttributeService} from '../../form-attribute/form-attribute.service';
import {FormAttributeEditComponent} from '../../form-attribute/form-attribute-edit/form-attribute-edit.component';


@Component({
  selector: 'app-form',
  templateUrl: './form-list.component.html',
  styleUrls: ['./form-list.component.css'],
  providers: [FormService,FormAttributeService]
})
export class FormListComponent extends AbstractCRUDComponent<FormMeta> {

  listAtribute: any[];

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
      this.loadFormAtributeByTableId(item.id);
      sub.unsubscribe();
    });
  }

  editAttribute(item: FormAttributeMeta, index: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponentOptions());
    const modalRef = this.modalService.show(FormAttributeEditComponent, config);
    const modal: AbstractModalComponent<FormAttributeMeta> = <AbstractModalComponent<FormAttributeMeta>>modalRef.content;
    modal.setModel(item);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      sub.unsubscribe();
    });
  }

  loadFormAtribute(item: FormMeta) {
    this.loadFormAtributeByTableId(item.id);
  }

  loadFormAtributeByTableId(formId: number) {
    const param = {
      form_id: formId,
    };
    this.formAttributesService.loadByParams(param).subscribe((res: any) => {
        this.listAtribute[formId] = res;
      }, () => {
        this.listAtribute[formId] = [];
      }
    );
  }

  removeAtribute(item: FormAttributeMeta, index: number, id: number) {
    this.formAttributesService.destroy(item['id']).subscribe(() => {
      this.loadFormAtributeByTableId(id);
      this.formAttributesService.toastSuccessfullyDeleted();
    }, () => this.formAttributesService.toastFailedDeleted());

  }

  constructor(
    service: FormService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  private formAttributesService: FormAttributeService,
  ) {
    super(service, modal, builder);
  }

  public load() {
    let params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, this.searchForm.value, true);
    this.service.loadByPage(params).subscribe(res => {
        this.list = res.data;
        this.pagination.set(res);
        this.listAtribute = [];
        this.list.forEach(position => {
          this.listAtribute[position.id] = [];
        });
      }, () => {
        this.listAtribute = [];
        this.list = [];
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
        this.listAtribute = [];
      }
    );
  }
}

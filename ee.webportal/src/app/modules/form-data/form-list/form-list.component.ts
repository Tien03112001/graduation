import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ObjectUtil} from '../../../core/utils';
import {AppPagination, PaginationOutput} from '../../../core/common';
import {FormService} from '../../form/form.service';
import {FormMeta} from '../../form/form.meta';
import {FormCreateComponent} from '../../form/form-create/form-create.component';
import {FormEditComponent} from '../../form/form-edit/form-edit.component';
import {FormDataService} from '../form-data.service';
import {FormAttributesService} from '../form-attributes.service';

@Component({
  selector: 'app-form',
  templateUrl: './form-list.component.html',
  styleUrls: ['./form-list.component.css'],
  providers: [FormService,FormDataService,FormAttributesService]
})
export class FormList1Component extends AbstractCRUDComponent<FormMeta> {

  listData: any[];
  listAtribute: any[];
  listPaginations: AppPagination[];

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý dữ liệu form';
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

  FormDataSetPagination(tableId: number, output: PaginationOutput<any>) {
    this.listPaginations[tableId] = new AppPagination();
    this.listPaginations[tableId].set(output);
  }

  loadFormData(item: FormMeta) {
    this.loadTableDataByTableId(item.id);
    this.loadFormAtributeByTableId(item.id);
  }

  loadTableDataByTableId(formId: number) {
    const param = {
      form_id: formId,
      limit: this.listPaginations[formId].itemsPerPage,
      page: this.listPaginations[formId].currentPage,
      orderBy: 'id:desc'
    };
    this.formDataService.loadByParams(param).subscribe((res: any) => {
        this.listData[formId] = res.data;
        this.FormDataSetPagination(formId, res);
      }, () => {
        this.listData[formId] = [];
        this.listPaginations[formId] = new AppPagination();
      }
    );
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

  public tablePageChanged(event: any, tableId: number) {
    this.listPaginations[tableId].currentPage = event.page;
    this.loadTableDataByTableId(tableId);
  }

  constructor(
    service: FormService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private formDataService: FormDataService,
    private formAttributesService: FormAttributesService,
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
        this.listData = [];
        this.listAtribute = [];
        this.listPaginations = [];
        this.list.forEach(position => {
          this.listData[position.id] = [];
          this.listAtribute[position.id] = [];
          this.listPaginations[position.id] = new AppPagination();
        });
      }, () => {
      this.listAtribute = [];
        this.list = [];
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
        this.listData = [];
        this.listPaginations = [];
      }
    );
  }
}

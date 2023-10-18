import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {FormDataService} from '../form-data.service';
import {FormDataCreateComponent} from '../form-data-create/form-data-create.component';
import {FormDataEditComponent} from '../form-data-edit/form-data-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {ModalResult} from '../../../core/common';

@Component({
  selector: 'app-form-data',
  templateUrl: './form-data-list.component.html',
  styleUrls: ['./form-data-list.component.css'],
  providers: [FormDataService]
})
export class FormDataListComponent extends AbstractCRUDComponent<any> {
  tableOneColumnData: any = [];
  panelOpenState: boolean = false;
  selectedIndex: number;

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý form dữ liệu';
  }

  getCreateModalComponent(): any {
    return FormDataCreateComponent;
  }

  getEditModalComponent(): any {
    return FormDataEditComponent;
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

  initNewModel(): any {
    return null;
  }
  edit1(form_value, j: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponent());
    const modalRef = this.modalService.show(FormDataEditComponent, config);
    const modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    modal.setModel(form_value)
  }
  public create1(form_value, j: number) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getCreateModalComponent());
    const modalRef = this.modalService.show(FormDataCreateComponent, config);
    const modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    modal.setModel(form_value);
    const sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        // item.columns = result.data.columns;
        // item.rows = result.data.rows;
        // item.cells = result.data.cells;
      }
      sub.unsubscribe();
    });
  }

  deleteRow(){

  }

  constructor(
    service: FormDataService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }
}

import {Component} from '@angular/core';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {FormDataService} from '../form-data.service';

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
    return null;
  }

  getEditModalComponent(): any {
    return null;
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

  constructor(
    service: FormDataService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }
}

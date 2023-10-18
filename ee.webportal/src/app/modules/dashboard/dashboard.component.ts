import {Component} from '@angular/core';
import {TitleService} from '../../core/services';
import {AbstractCRUDComponent} from '../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {DashboardService} from './dashboard.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css'],
  providers: [DashboardService]
})
export class DashboardComponent extends AbstractCRUDComponent<any> {



  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  getCreateModalComponent(): any {
  }

  getCreateModalComponentOptions(): ModalOptions {
    return undefined;
  }

  getEditModalComponent(): any {
  }

  getEditModalComponentOptions(): ModalOptions {
    return undefined;
  }

  getTitle(): string {
    return 'Bảng điều khiển';
  }

  initNewModel(): any {
    return undefined;
  }

  onDestroy(): void {
  }

  onInit(): void {
    this.load();
  }

  constructor(
    service: DashboardService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
  }



}

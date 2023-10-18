import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {AbstractCRUDComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {CustomerMeta} from '../customer.meta';
import {CustomerService} from '../customer.service';
import {CustomerCreateComponent} from '../customer-create/customer-create.component';
import {CustomerEditComponent} from '../customer-edit/customer-edit.component';

@Component({
  selector: 'app-customer-list',
  templateUrl: './customer-list.component.html',
  styleUrls: ['./customer-list.component.css'],
  providers: [CustomerService]
})
export class CustomerListComponent extends AbstractCRUDComponent<CustomerMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý khách hàng';
  }

  getCreateModalComponent(): any {
    return CustomerCreateComponent;
  }

  getEditModalComponent(): any {
    return CustomerEditComponent;
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
      is_mine: new FormControl(0),
    });
  }

  initNewModel(): CustomerMeta {
    return new CustomerMeta();
  }

  constructor(
    service: CustomerService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
  }

}

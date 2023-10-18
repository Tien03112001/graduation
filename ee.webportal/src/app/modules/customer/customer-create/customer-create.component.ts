import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {CustomerMeta} from '../customer.meta';
import {CustomerService} from '../customer.service';

@Component({
  selector: 'app-customer-create',
  templateUrl: './customer-create.component.html',
  styleUrls: ['./customer-create.component.css'],
  providers: [CustomerService]
})
export class CustomerCreateComponent extends AbstractModalComponent<CustomerMeta> {

  onInit(): void {
  }

  // loadInfo() {
  //   this.roleService.loadAll().subscribe((data: Role[]) => {
  //     this.list_role = data;
  //     if (this.list_role.length > 0) this.formGroup.controls['role_id'].setValue(this.list_role[0].id);
  //   }, err => LoggingUtil.d(err));
  // }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      phone: new FormControl(null, Validators.required),
      email: new FormControl(null, Validators.required),
      description: new FormControl(null)
    });
  }

  loaded(): void {
  }

  constructor(
    service: CustomerService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

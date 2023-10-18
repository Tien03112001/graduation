import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ShippingStatusMeta} from '../shipping-status.meta';
import {ShippingStatusService} from '../shipping-status.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-shipping-status-create',
  templateUrl: './shipping-status-create.component.html',
  styleUrls: ['./shipping-status-create.component.css'],
  providers: [ShippingStatusService]
})
export class ShippingStatusCreateComponent extends AbstractModalComponent<ShippingStatusMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ShippingStatusService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }
}

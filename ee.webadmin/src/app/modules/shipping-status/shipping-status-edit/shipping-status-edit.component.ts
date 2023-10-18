import {Component, ViewChild} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ShippingStatusMeta} from '../shipping-status.meta';
import {ShippingStatusService} from '../shipping-status.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-shipping-status-edit',
  templateUrl: './shipping-status-edit.component.html',
  styleUrls: ['./shipping-status-edit.component.css'],
  providers: [ShippingStatusService]
})
export class ShippingStatusEditComponent extends AbstractModalComponent<ShippingStatusMeta> {

  onParentChange() {
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên*', 'name', 'Nhập kí tự'),

    ];
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),

    });
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

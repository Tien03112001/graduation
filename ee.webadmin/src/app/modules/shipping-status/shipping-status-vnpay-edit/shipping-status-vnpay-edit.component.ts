import {Component, ViewChild} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ShippingStatusMeta} from '../shipping-status.meta';
import {ShippingStatusService} from '../shipping-status.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-shipping-status-vnpay-edit',
  templateUrl: './shipping-status-vnpay-edit.component.html',
  styleUrls: ['./shipping-status-vnpay-edit.component.css'],
  providers: [ShippingStatusService]
})
export class ShippingStatusEditVnpayComponent extends AbstractModalComponent<ShippingStatusMeta> {

  onParentChange() {
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('VNP Url *', 'vnp_Url', 'Nhập kí tự'),
      FieldForm.createTextInput('VNP TmnCode *', 'vnp_TmnCode', 'Nhập kí tự'),
      FieldForm.createTextInput('VNP HashSecret *', 'vnp_HashSecret', 'Nhập kí tự'),
      FieldForm.createTextInput('VNP Locale *', 'vnp_Locale', 'Nhập kí tự'),
      FieldForm.createTextInput('VNP Version *', 'vnp_Version', 'Nhập kí tự'),
    ];
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      vnp_Url: new FormControl(null, Validators.required),
      vnp_TmnCode: new FormControl(null, Validators.required),
      vnp_HashSecret: new FormControl(null, Validators.required),
      vnp_Locale: new FormControl(null, Validators.required),
      vnp_Version: new FormControl(null, Validators.required),
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

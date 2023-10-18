import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PaymentService} from '../payment.service';
import {PaymentMeta} from '../payment.meta';
import { AbstractModalComponent } from '../../../core/crud';
import { FieldForm } from '../../../core/common';


@Component({
  selector: 'app-payment-edit',
  templateUrl: './payment-edit.component.html',
  styleUrls: ['./payment-edit.component.css'],
  providers: [PaymentService]
})
export class PaymentEditComponent extends AbstractModalComponent<PaymentMeta> {

  onInit(): void {
  }


  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.maxLength(255), Validators.required, Validators.pattern('[^ ].*$')]),
      description: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên hình thức thanh toán *', 'name', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: PaymentService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

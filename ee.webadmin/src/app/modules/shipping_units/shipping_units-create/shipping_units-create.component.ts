import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {ShippingUnitsMeta} from '../shipping_units.meta';
import {ShippingUnitsService} from '../shipping_units.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-shipping_units-create',
  templateUrl: './shipping_units-create.component.html',
  styleUrls: ['./shipping_units-create.component.css'],
  providers: [ShippingUnitsService]
})
export class ShippingUnitsCreateComponent extends AbstractModalComponent<ShippingUnitsMeta> {
  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')]),
      username: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')]),
      password: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')]),
      token: new FormControl(null,[Validators.required, Validators.pattern('[^ ].*$')]),
      logo: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')]),
      endpoint: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')]),
      config: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')]),
      class_name: new FormControl(null, [Validators.required, Validators.pattern('[^ ].*$')])
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên đối tác vận chuyển *', 'name', 'Nhập tên đối tác'),
      FieldForm.createTextInput('User name đăng nhập', 'username', 'Nhập tên đăng nhập'),
      FieldForm.createTextInput('Password', 'password', 'Nhập tên đăng nhập'),
      FieldForm.createTextInput('Link Logo', 'logo', 'Nhập link logo'),
      FieldForm.createTextInput('Token', 'token', 'Nhập ký tự'),
      FieldForm.createTextInput('End point', 'endpoint', 'Nhập ký tự'),
      FieldForm.createTextInput('Config', 'config', 'Nhập ký tự'),
      FieldForm.createTextInput('Class name', 'class_name', 'Nhập ký tự')
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ShippingUnitsService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

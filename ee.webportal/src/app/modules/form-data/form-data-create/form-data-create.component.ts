import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import {FormDataService} from '../form-data.service';
import {FormDataMeta} from '../form-data.meta';

@Component({
  selector: 'app-form-data-create',
  templateUrl: './form-data-create.component.html',
  styleUrls: ['./form-data-create.component.css'],
  providers: [FormDataService]
})
export class FormDataCreateComponent extends AbstractModalComponent<FormDataMeta> {
  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      value: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập ký tự'),
      FieldForm.createTextInput('giá trị', 'value', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: FormDataService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

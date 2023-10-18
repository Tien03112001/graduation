import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FormAttributeMeta} from '../form-attribute.meta';
import {FormAttributeService} from '../form-attribute.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-form-attribute-create',
  templateUrl: './form-attribute-create.component.html',
  styleUrls: ['./form-attribute-create.component.css'],
  providers: [FormAttributeService]
})
export class FormAttributeCreateComponent extends AbstractModalComponent<FormAttributeMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      description: new FormControl(null),
      form_id: new FormControl(null)
    });
  }
  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên*', 'name', 'Nhập tên'),
      FieldForm.createTextInput('Mô tả', 'description', 'Nhập mô tả'),
    ];
  }

  loaded(): void {
  }


  constructor(
    service: FormAttributeService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

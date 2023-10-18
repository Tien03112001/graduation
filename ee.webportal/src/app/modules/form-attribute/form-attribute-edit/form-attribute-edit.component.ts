import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import {FormAttributeMeta} from '../form-attribute.meta';
import {FormAttributeService} from '../form-attribute.service';


@Component({
  selector: 'app-form-attribute-edit',
  templateUrl: './form-attribute-edit.component.html',
  styleUrls: ['./form-attribute-edit.component.css'],
  providers: [FormAttributeService]
})
export class FormAttributeEditComponent extends AbstractModalComponent<FormAttributeMeta> {

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

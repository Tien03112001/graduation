import { Component } from '@angular/core';
import { AbstractModalComponent } from '../../../core/crud';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { StructuredDataPropertyMeta } from '../structured-data-property.meta';
import { StructuredDataPropertyService } from '../structured-data-property.service';
import { FieldForm } from '../../../core/common';
import { ObjectUtil } from '../../../core/utils';

@Component({
  selector: 'app-structured-data-property-edit',
  templateUrl: './structured-data-property-edit.component.html',
  styleUrls: ['./structured-data-property-edit.component.css'],
  providers: [StructuredDataPropertyService]
})
export class StructuredDataPropertyEditComponent extends AbstractModalComponent<StructuredDataPropertyMeta> {
  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }


  loaded(): void {
    let valueField = FieldForm.createTextInput('Giá trị', 'value', 'Nhập giá trị');
    if (this.model.value_type == 'select') {
      let selectData = this.model.possible_values.split(',').map(v => {
        return { id: v, name: v };
      });
      valueField = FieldForm.createSelect('Giá trị', 'value', 'Nhập giá trị', selectData);
    }
    if (this.model.value_type == 'file') {
      valueField = FieldForm.createFileInput('Chọn file', 'value', 'chọn file', this.onFileUploadChange);
    }
    if (this.model.value_type == 'array' || this.model.value_type == 'json') {
      valueField = FieldForm.createTextArea('Giá trị', 'value', 'Nhập giá trị');
    }
    if (this.model.value_type == 'url') {
      valueField = FieldForm.createTextInput('Giá trị', 'value', 'http://');
    }
    if (this.model.value_type == 'numeric') {
      valueField = FieldForm.createNumberInput('Giá trị', 'value', 'Nhập giá trị');
    }
    this.fields.push(valueField);
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.pattern('^[a-zA-Z0-9 @]+$')]),
      value: new FormControl(null, Validators.maxLength(255)),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
    ];
  }

  constructor(
    service: StructuredDataPropertyService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  onFileUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }


}

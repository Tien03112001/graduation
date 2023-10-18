import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableRowService} from '../dynamic-table-row.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-dynamic-table-create-row',
  templateUrl: './dynamic-table-row-create.component.html',
  styleUrls: ['./dynamic-table-row-create.component.css'],
  providers: [DynamicTableRowService]
})
export class DynamicTableRowCreateComponent extends AbstractModalComponent<any> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      id: new FormControl(null, Validators.required),
    });
  }

  public initFieldForm(): FieldForm[] {
    return [];
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }

  loaded(): void {
    for (let i = 0; i < this.model.columns.length; i++) {
      let formControlName = `${this.model.columns[i].id}`;
      this.formGroup.addControl(formControlName, new FormControl(null, [Validators.required]));
      let fieldForm = FieldForm.createTextInput(this.model.columns[i].name, formControlName, 'Nhập thông tin');

      if (this.model.columns[i].type === 'image') {
        fieldForm = FieldForm.createFileInput(this.model.columns[i].name + ' *', formControlName, 'chọn ảnh', this.onImageUploadChange, 'image/*');
      } else if (this.model.columns[i].type === 'file') {
        fieldForm = FieldForm.createFileInput(this.model.columns[i].name + ' *', formControlName, 'chọn file', this.onFileUploadChange);
      } else if (this.model.columns[i].type === 'text') {
        fieldForm = FieldForm.createTextInput(this.model.columns[i].name + ' *', formControlName, 'Nhập thông tin');
      } else if (this.model.columns[i].type === 'number') {
        fieldForm = FieldForm.createNumberInput(this.model.columns[i].name + ' *', formControlName, 'Nhập thông tin');
      } else if (this.model.columns[i].type === 'textarea') {
        fieldForm = FieldForm.createTextArea(this.model.columns[i].name + ' *', formControlName, 'Nhập thông tin');
      } else if (this.model.columns[i].type === 'html') {
        fieldForm = FieldForm.createHtmlInput(this.model.columns[i].name + ' *', formControlName, 'Nhập thông tin');
      }
      this.fields.push(fieldForm);
    }
  }

  constructor(
    service: DynamicTableRowService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onFormChanged(): void {
    super.onFormChanged();
  }
}

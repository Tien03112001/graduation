import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableAdminColumnService} from '../dynamic-table-admin-column.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-dynamic-table-admin-column-edit',
  templateUrl: './dynamic-table-admin-column-edit.component.html',
  styleUrls: ['./dynamic-table-admin-column-edit.component.css'],
  providers: [DynamicTableAdminColumnService]
})
export class DynamicTableAdminColumnEditComponent extends AbstractModalComponent<any> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      description: new FormControl(null),
      type: new FormControl(null)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên cột'),
      FieldForm.createSelect('Kiểu dữ liệu', 'type', 'Chọn một', [
        {
          value: 'text',
          name: 'Ký tự',
        },
        {
          value: 'number',
          name: 'Số',
        },
        {
          value: 'image',
          name: 'Hình ảnh',
        },
        {
          value: 'html',
          name: 'HTML',
        }
      ], null, 'name', 'value'),
      FieldForm.createTextInput('Mô tả', 'description', 'Nhập mô tả'),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      name: this.model.name,
      description: this.model.description,
      type: this.model.type,
    });
  }

  constructor(
    service: DynamicTableAdminColumnService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

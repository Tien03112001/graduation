import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableAdminColumnMeta} from '../dynamic-table-admin-column.meta';
import {DynamicTableAdminColumnService} from '../dynamic-table-admin-column.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-dynamic-table-create-column',
  templateUrl: './dynamic-table-admin-column-create.component.html',
  styleUrls: ['./dynamic-table-admin-column-create.component.css'],
  providers: [DynamicTableAdminColumnService]
})
export class DynamicTableAdminColumnCreateComponent extends AbstractModalComponent<DynamicTableAdminColumnMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      description: new FormControl(null),
      type: new FormControl('text', Validators.required)
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
          value: 'textarea',
          name: 'Nhiều ký tự',
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
          value: 'file',
          name: 'File',
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
  }

  constructor(
    service: DynamicTableAdminColumnService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }
}

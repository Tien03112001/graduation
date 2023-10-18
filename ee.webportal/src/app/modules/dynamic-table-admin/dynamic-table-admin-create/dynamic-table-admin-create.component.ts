import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableAdminService} from '../dynamic-table-admin.service';
import {DynamicTableAdminMeta} from '../dynamic-table-admin.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-menu-position-create',
  templateUrl: './dynamic-table-admin-create.component.html',
  styleUrls: ['./dynamic-table-admin-create.component.css'],
  providers: [DynamicTableAdminService]
})
export class DynamicTableAdminCreateComponent extends AbstractModalComponent<DynamicTableAdminMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      description: new FormControl(null)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên bảng'),
      FieldForm.createTextInput('Mô tả', 'description', 'Nhập mô tả'),
    ];
  }

  loaded(): void {
  }


  constructor(
    service: DynamicTableAdminService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

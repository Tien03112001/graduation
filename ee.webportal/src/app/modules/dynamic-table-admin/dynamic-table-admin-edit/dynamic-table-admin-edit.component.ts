import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableAdminMeta} from '../dynamic-table-admin.meta';
import {DynamicTableAdminService} from '../dynamic-table-admin.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-dynamic-table-admin-edit',
  templateUrl: './dynamic-table-admin-edit.component.html',
  styleUrls: ['./dynamic-table-admin-edit.component.css'],
  providers: [DynamicTableAdminService]
})
export class DynamicTableAdminEditComponent extends AbstractModalComponent<DynamicTableAdminMeta> {

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
    this.formGroup.setValue({
      name: this.model.name,
      description: this.model.description,
    });
  }

  constructor(
    service: DynamicTableAdminService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { FieldForm } from '../../../core/common';
import { AbstractModalComponent } from '../../../core/crud';
import { TitleService } from '../../../core/services';
import { UserRoleMeta } from '../user-role.meta';
import { UserRoleService } from '../user-role.service';

@Component({
  selector: 'app-User-role-edit',
  templateUrl: './user-role-edit.component.html',
  styleUrls: ['./user-role-edit.component.css'],
  providers: [UserRoleService]
})
export class UserRoleEditComponent extends AbstractModalComponent<UserRoleMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên đầy đủ', 'name', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: UserRoleService,
    modal: BsModalRef,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

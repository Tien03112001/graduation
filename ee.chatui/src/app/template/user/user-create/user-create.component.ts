import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent, FieldForm} from '../../../core';
import {UserMeta} from '../user.meta';
import {UserService} from '../user.service';

@Component({
  selector: 'app-user-create',
  templateUrl: './user-create.component.html',
  styleUrls: ['./user-create.component.css'],
  providers: [UserService]
})
export class UserCreateComponent extends AbstractModalComponent<UserMeta> {
  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      username: new FormControl(null, [Validators.minLength(6), Validators.required, Validators.pattern('/\w+/')]),
      password: new FormControl(null, [Validators.minLength(6), Validators.required, Validators.pattern('/\w+/')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập ký tự'),
      FieldForm.createTextInput('Tài khoản (Tối thiểu 6 ký tự)', 'username', 'Nhập ký tự'),
      FieldForm.createTextInput('Mật khẩu (Tối thiểu 6 ký tự)', 'password', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: UserService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

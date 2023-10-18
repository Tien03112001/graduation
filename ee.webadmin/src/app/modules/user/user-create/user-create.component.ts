import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {UserMeta} from '../user.meta';
import {UserService} from '../user.service';
import {FieldForm} from '../../../core/common';

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
      name: new FormControl(null, [Validators.maxLength(255), Validators.required, Validators.pattern('^(?=.*[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$')]),
      username: new FormControl(null, [Validators.required, Validators.maxLength(255), , Validators.pattern('^(?=.*[a-zA-Z0-9]+)[a-zA-Z0-9]*$')]),
      password: new FormControl(null, [Validators.minLength(6), Validators.required, Validators.pattern('^(?=.*[a-zA-Z0-9]+)[^-\\s][a-zA-Z0-9\ ~`!@#$%^&*(){}<>\+-=?/:;,."\'_|]*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Họ và tên *', 'name', 'Nhập ký tự'),
      FieldForm.createTextInput('Tên đăng nhập *', 'username', 'Nhập ký tự'),
      FieldForm.createPasswordInput('Mật khẩu *', 'password', 'Nhập ký tự'),
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

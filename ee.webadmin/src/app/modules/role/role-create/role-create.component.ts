import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {RoleMeta} from '../role.meta';
import {RoleService} from '../role.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-role-create',
  templateUrl: './role-create.component.html',
  styleUrls: ['./role-create.component.css'],
  providers: [RoleService]
})
export class RoleCreateComponent extends AbstractModalComponent<RoleMeta> {
  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$')]),
      description: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập ký tự'),
      FieldForm.createTextInput('Mô tả', 'description', 'Nhập ký tự')
    ];
  }

  loaded(): void {
  }

  constructor(
    service: RoleService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

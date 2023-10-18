import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import {AgentService} from '../agent.service';
import {AgentMeta} from '../agent.meta';


@Component({
  selector: 'app-Agent-edit',
  templateUrl: './agent-edit.component.html',
  styleUrls: ['./agent-edit.component.css'],
  providers: [AgentService]
})
export class AgentEditComponent extends AbstractModalComponent<AgentMeta> {

  onInit(): void {
  }


  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      usertname: new FormControl(null, [Validators.minLength(6), Validators.required, Validators.pattern('/\w+/')]),
      password: new FormControl(null, [Validators.minLength(6), Validators.required, Validators.pattern('/\w+/')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập ký tự'),
      FieldForm.createTextInput('Tài khoản (Tối thiểu 6 ký tự)', 'usertname', 'Nhập ký tự'),
      FieldForm.createTextInput('Mật khẩu (Tối thiểu 6 ký tự)', 'password', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: AgentService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

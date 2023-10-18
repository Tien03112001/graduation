import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {StatusMeta} from '../status.meta';
import {StatusService} from '../status.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-status-create',
  templateUrl: './status-create.component.html',
  styleUrls: ['./status-create.component.css'],
  providers: [StatusService]
})
export class StatusCreateComponent extends AbstractModalComponent<StatusMeta> {
  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      description: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên trạng thái đơn hàng *', 'name', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: StatusService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

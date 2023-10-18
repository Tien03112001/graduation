import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {ChanelMeta} from '../chanel.meta';
import {ChanelService} from '../chanel.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-chanel-create',
  templateUrl: './chanel-create.component.html',
  styleUrls: ['./chanel-create.component.css'],
  providers: [ChanelService]
})
export class ChanelCreateComponent extends AbstractModalComponent<ChanelMeta> {
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
      FieldForm.createTextInput('Tên *', 'name', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ChanelService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

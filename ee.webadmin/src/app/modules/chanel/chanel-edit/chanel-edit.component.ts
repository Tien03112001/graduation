import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ChanelService} from '../chanel.service';
import {ChanelMeta} from '../chanel.meta';
import { AbstractModalComponent } from '../../../core/crud';
import { FieldForm } from '../../../core/common';


@Component({
  selector: 'app-chanel-edit',
  templateUrl: './chanel-edit.component.html',
  styleUrls: ['./chanel-edit.component.css'],
  providers: [ChanelService]
})
export class ChanelEditComponent extends AbstractModalComponent<ChanelMeta> {

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
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

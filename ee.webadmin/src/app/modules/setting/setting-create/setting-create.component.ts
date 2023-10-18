import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {SettingMeta} from '../setting.meta';
import {BsModalRef} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {SettingService} from '../setting.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-setting-create',
  templateUrl: './setting-create.component.html',
  styleUrls: ['./setting-create.component.css'],
  providers: [SettingService]
})
export class SettingCreateComponent extends AbstractModalComponent<SettingMeta> {

  constructor(
    service: SettingService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  ngOnInit() {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      value: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
      FieldForm.createTextInput('Giá trị *', 'value', 'Nhập giá trị'),
    ];
  }

  loaded(): void {
  }

  onDestroy(): void {
  }

  onInit(): void {
  }

}

import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {SettingMeta} from '../setting.meta';
import {SettingService} from '../setting.service';
import {BsModalRef} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-setting-edit',
  templateUrl: './setting-edit.component.html',
  styleUrls: ['./setting-edit.component.css'],
  providers: [SettingService]
})
export class SettingEditComponent extends AbstractModalComponent<SettingMeta> {

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
      name: new FormControl({value: null, disabled: true}, Validators.required),
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
    this.formGroup.setValue({
      name: this.model.name,
      value: this.model.value,
    });
  }

  onDestroy(): void {
  }

  onInit(): void {
  }

}

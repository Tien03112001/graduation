import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {MenuPositionService} from '../menu-position.service';
import {MenuPositionMeta} from '../menu-position.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-menu-position-edit',
  templateUrl: './menu-position-edit.component.html',
  styleUrls: ['./menu-position-edit.component.css'],
  providers: [MenuPositionService]
})
export class MenuPositionEditComponent extends AbstractModalComponent<MenuPositionMeta> {

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
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      name: this.model.name,
    });
  }

  constructor(
    service: MenuPositionService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

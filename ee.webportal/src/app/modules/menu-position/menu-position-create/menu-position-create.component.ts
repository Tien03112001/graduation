import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {MenuPositionService} from '../menu-position.service';
import {MenuPositionMeta} from '../menu-position.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-menu-position-create',
  templateUrl: './menu-position-create.component.html',
  styleUrls: ['./menu-position-create.component.css'],
  providers: [MenuPositionService]
})
export class MenuPositionCreateComponent extends AbstractModalComponent<MenuPositionMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null,[Validators.required, Validators.maxLength(255), Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s].*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: MenuPositionService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

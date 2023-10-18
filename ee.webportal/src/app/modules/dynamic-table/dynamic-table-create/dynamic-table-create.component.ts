import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableService} from '../dynamic-table.service';
import {DynamicTableMeta} from '../dynamic-table.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-menu-position-create',
  templateUrl: './dynamic-table-create.component.html',
  styleUrls: ['./dynamic-table-create.component.css'],
  providers: [DynamicTableService]
})
export class DynamicTableCreateComponent extends AbstractModalComponent<DynamicTableMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      description: new FormControl(null)
    });
  }
  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập ký tự'),
      FieldForm.createTextInput('Mô tả', 'description', 'Nhập ký tự'),
    ];
  }

  loaded(): void {
  }


  constructor(
    service: DynamicTableService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

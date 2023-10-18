import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {CompanyService} from '../company.service';
import {CompanyMeta} from '../company.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-sale-create',
  templateUrl: './company-create.component.html',
  styleUrls: ['./company-create.component.css'],
  providers: [CompanyService]
})
export class CompanyCreateComponent extends AbstractModalComponent<CompanyMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
      value: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('[^ ].*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên*', 'name', 'Nhập ký tự tối đa 255'),
      FieldForm.createTextInput('Giá trị*', 'value', 'Nhập ký tự tối đa 255'),
    ];
  }

  loaded(): void {
  }


  constructor(
    service: CompanyService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

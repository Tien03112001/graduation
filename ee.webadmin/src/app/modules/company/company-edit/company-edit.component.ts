import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {CompanyMeta} from '../company.meta';
import {CompanyService} from '../company.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-sale-edit',
  templateUrl: './company-edit.component.html',
  styleUrls: ['./company-edit.component.css'],
  providers: [CompanyService]
})
export class CompanyEditComponent extends AbstractModalComponent<CompanyMeta> {


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
      FieldForm.createTextInput('Tên', 'name', 'Nhập ký tự tối đa 255'),
      FieldForm.createTextInput('Giá trị', 'value', 'Nhập ký tự tối đa 255'),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      name: this.model.name,
      value: this.model.value
    });
  }

  constructor(
    service: CompanyService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

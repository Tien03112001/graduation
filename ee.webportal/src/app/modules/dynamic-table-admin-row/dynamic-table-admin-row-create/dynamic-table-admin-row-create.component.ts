import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableAdminRowService} from '../dynamic-table-admin-row.service';

@Component({
  selector: 'app-dynamic-table-create-row',
  templateUrl: './dynamic-table-admin-row-create.component.html',
  styleUrls: ['./dynamic-table-admin-row-create.component.css'],
  providers: [DynamicTableAdminRowService]
})
export class DynamicTableAdminRowCreateComponent extends AbstractModalComponent<any> {

  fields = [];
  formControls = {};

  onInit(): void {
    this.formControls = this.fields;
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({});
  }

  loaded(): void {
    for (let i = 0; i < this.model.columns.length; i++) {
      let fieldForm = {
        label: this.model.columns[i].name,
        type: 'input',
        typeOf: 'text',
        formControl: this.model.columns[i].id,
        placeHolder: 'Nhập thông tin'
      };
      this.formGroup.addControl(this.model.columns[i].id, new FormControl(null, Validators.required));
      this.fields.push(fieldForm);
    }
  }


  constructor(
    service: DynamicTableAdminRowService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

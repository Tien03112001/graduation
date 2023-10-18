import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableAdminRowService} from '../dynamic-table-admin-row.service';
import {ObjectUtil} from '../../../core/utils';

@Component({
  selector: 'app-dynamic-table-admin-row-edit',
  templateUrl: './dynamic-table-admin-row-edit.component.html',
  styleUrls: ['./dynamic-table-admin-row-edit.component.css'],
  providers: [DynamicTableAdminRowService]
})
export class DynamicTableAdminRowEditComponent extends AbstractModalComponent<any> {

  fields = [];
  formControls = {};

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({});
  }

  edit() {
    let item: any = ObjectUtil.combineValue(this.model, this.formGroup.value);
    this.service.update(item).subscribe((res: any) => {
      this.service.toastSuccessfullyEdited();
      this.close(res);
    }, () => this.service.toastFailedEdited());
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
      this.formGroup.addControl(this.model.columns[i].id, new FormControl(this.model.columns[i].cell.cell_value, Validators.required));
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

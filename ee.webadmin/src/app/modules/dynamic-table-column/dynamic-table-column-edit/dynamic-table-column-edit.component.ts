import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableColumnService} from '../dynamic-table-column.service';

@Component({
  selector: 'app-dynamic-table-column-edit',
  templateUrl: './dynamic-table-column-edit.component.html',
  styleUrls: ['./dynamic-table-column-edit.component.css'],
  providers: [DynamicTableColumnService]
})
export class DynamicTableColumnEditComponent extends AbstractModalComponent<any> {

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


  loaded(): void {
    console.log(this.model);
    this.formGroup.setValue({
      name: this.model.name,
      description: this.model.description
    });
  }

  constructor(
    service: DynamicTableColumnService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

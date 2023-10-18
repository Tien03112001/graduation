import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableMeta} from '../dynamic-table.meta';
import {DynamicTableService} from '../dynamic-table.service';

@Component({
  selector: 'app-dynamic-table-edit',
  templateUrl: './dynamic-table-edit.component.html',
  styleUrls: ['./dynamic-table-edit.component.css'],
  providers: [DynamicTableService]
})
export class DynamicTableEditComponent extends AbstractModalComponent<DynamicTableMeta> {

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
    this.formGroup.setValue({
      name: this.model.name,
    });
  }

  constructor(
    service: DynamicTableService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

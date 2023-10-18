import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {DynamicTableColumnMeta} from '../dynamic-table-column.meta';
import {DynamicTableColumnService} from '../dynamic-table-column.service';

@Component({
  selector: 'app-dynamic-table-create-column',
  templateUrl: './dynamic-table-column-create.component.html',
  styleUrls: ['./dynamic-table-column-create.component.css'],
  providers: [DynamicTableColumnService]
})
export class DynamicTableColumnCreateComponent extends AbstractModalComponent<DynamicTableColumnMeta> {

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
  }


  constructor(
    service: DynamicTableColumnService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

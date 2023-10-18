import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {StructuredDataTypeMeta} from '../structured-data-type.meta';
import {StructuredDataTypeService} from '../structured-data-type.service';

@Component({
  selector: 'app-structured-data-type-edit',
  templateUrl: './structured-data-type-edit.component.html',
  styleUrls: ['./structured-data-type-edit.component.css'],
  providers: [StructuredDataTypeService]
})
export class StructuredDataTypeEditComponent extends AbstractModalComponent<StructuredDataTypeMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255)]),
    });
  }


  loaded(): void {
    this.formGroup.setValue({
      name: this.model.name,
    });
  }

  constructor(
    service: StructuredDataTypeService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AttributeTypeMeta} from '../attribute-type.meta';
import {AttributeTypeService} from '../attribute-type.service';

@Component({
  selector: 'app-attribute-type-create',
  templateUrl: './attribute-type-create.component.html',
  styleUrls: ['./attribute-type-create.component.css'],
  providers: [AttributeTypeService]
})
export class AttributeTypeCreateComponent extends AbstractModalComponent<AttributeTypeMeta> {
  options: any[] = [];

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      options: new FormControl([]),
    });
  }

  loaded(): void {
  }

  add() {
    this.options.push({});
  }

  removeNote(item: any, index: number) {
    this.options.splice(index, 1);
  }

  onCreate() {
    this.formGroup.controls['options'].setValue(JSON.stringify(this.options));
    this.create();
  }

  constructor(
    service: AttributeTypeService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }


}

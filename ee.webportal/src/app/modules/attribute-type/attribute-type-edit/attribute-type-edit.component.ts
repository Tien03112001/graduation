import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AttributeTypeMeta} from '../attribute-type.meta';
import {AttributeTypeService} from '../attribute-type.service';

@Component({
  selector: 'app-attribute-type-edit',
  templateUrl: './attribute-type-edit.component.html',
  styleUrls: ['./attribute-type-edit.component.css'],
  providers: [AttributeTypeService]
})
export class AttributeTypeEditComponent extends AbstractModalComponent<AttributeTypeMeta> {
  options: any[] = [];
  name: string = '';

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
    this.formGroup.setValue({
      name: this.model.name,
      options: this.model.options,
    });
    this.options = this.model.options;
    this.name = this.model.name;
  }

  add() {
    this.options.push({});
  }

  removeNote(item: any, index: number) {
    this.options.splice(index, 1);
  }

  onEdit() {
    this.formGroup.controls['options'].setValue(JSON.stringify(this.options));
    this.edit();
  }

  constructor(
    service: AttributeTypeService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

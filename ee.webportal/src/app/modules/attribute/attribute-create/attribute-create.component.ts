import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AttributeService} from '../attribute.service';
import {AttributeMeta} from '../attribute.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-attribute-create',
  templateUrl: './attribute-create.component.html',
  styleUrls: ['./attribute-create.component.css'],
  providers: [AttributeService]
})
export class AttributeCreateComponent extends AbstractModalComponent<AttributeMeta> {

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      code: new FormControl(null, Validators.required)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      {
        label: 'Tên',
        type: 'input',
        typeof: 'text',
        formControl: 'name',
        placeHolder: 'Bắt buộc',
      },
      {
        label: 'Mã',
        type: 'input',
        typeof: 'text',
        formControl: 'code',
        placeHolder: 'Bắt buộc',
      },
    ];
  }

  loaded(): void {
  }

  onDestroy(): void {
  }

  onInit(): void {
  }


  constructor(
    service: AttributeService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

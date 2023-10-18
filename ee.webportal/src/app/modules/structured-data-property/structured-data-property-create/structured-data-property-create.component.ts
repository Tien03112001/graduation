import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {StructuredDataPropertyMeta} from '../structured-data-property.meta';
import {StructuredDataPropertyService} from '../structured-data-property.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-structured-data-property-create',
  templateUrl: './structured-data-property-create.component.html',
  styleUrls: ['./structured-data-property-create.component.css'],
  providers: [StructuredDataPropertyService]
})
export class StructuredDataPropertyCreateComponent extends AbstractModalComponent<StructuredDataPropertyMeta> {
  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.pattern('^[a-zA-Z0-9 @]+$')]),
      value_type: new FormControl(null, Validators.required),
      possible_values: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
      FieldForm.createSelect('Loại *', 'value_type', 'Chọn một', [
        {
          value: 'text',
          name: 'Text',
        },{
          value: 'select',
          name: 'Select',
        },
        {
          value: 'numeric',
          name: 'Numeric',
        },
        {
          value: 'url',
          name: 'Url',
        },
        {
          value: 'file',
          name: 'File',
        },
        {
          value: 'array',
          name: 'Array',
        },
        {
          value: 'json',
          name: 'Json',
        },
        {
          value: 'structure_data',
          name: 'Structure Data',
        },
      ], 'col-md-6'),
      FieldForm.createTextInput('Giá trị khả thi', 'possible_values', 'Nhập giá trị khả thi(nếu có)'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: StructuredDataPropertyService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  onFormChanged() {
    super.onFormChanged();
    this.formGroup.controls['value_type'].valueChanges.subscribe(value => {
      if (value != 'select' || value == null) {
        this.formGroup.controls['possible_values'].disable();
      }
      if (value == 'select') {
        this.formGroup.controls['possible_values'].enable();
      }
    });
  }
}

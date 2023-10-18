import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {StructuredDataMeta} from '../structured-data.meta';
import {StructuredDataService} from '../structured-data.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-structured-data-edit',
  templateUrl: './structured-data-edit.component.html',
  styleUrls: ['./structured-data-edit.component.css'],
  providers: [StructuredDataService]
})
export class StructuredDataEditComponent extends AbstractModalComponent<StructuredDataMeta> {
  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      code: new FormControl(null,[Validators.required, Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s][a-zA-Z0-9~`!@#$%^&*()+=?:;"\'_ÂÀÁÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ ]*$')]),
      content: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Code *', 'code', 'Nhập code'),
      FieldForm.createTextInput('Nội dung *', 'content', 'Nhập nội dung'),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      code: this.model.code,
      content: this.model.content
    });
  }

  constructor(
    service: StructuredDataService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }
}

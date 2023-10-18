import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {MetaDataMeta} from '../meta-data.meta';
import {MetaDataService} from '../meta-data.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-meta-data-create',
  templateUrl: './meta-data-create.component.html',
  styleUrls: ['./meta-data-create.component.css'],
  providers: [MetaDataService]
})
export class MetaDataCreateComponent extends AbstractModalComponent<MetaDataMeta> {
  name: string = '';

  onDestroy(): void {
  }

  onInit(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      keywords: new FormControl(null),
      description: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s][a-zA-Z0-9~`!@#$%^&*()+=?:;"\'_ÂÀÁÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ ]*$')]),
      robots: new FormControl(null),
      canonical: new FormControl(null),
      metaable_type: new FormControl(null, Validators.required),
      metaable_id: new FormControl(null, Validators.required),
      title: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s][a-zA-Z0-9~`!@#$%^&*()+=?:;"\'_ÂÀÁÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ ]*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextArea('Description *', 'description', 'SEO Description'),
      FieldForm.createTextInput('Title *', 'title', 'Tiêu đề'),
      FieldForm.createTextInput('Keywords', 'keywords', 'SEO Keywords'),
      FieldForm.createTextInput('Robots', 'robots', 'SEO Robots'),
      FieldForm.createTextInput('Canonical', 'canonical', 'SEO Canonical'),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      keywords: null,
      description: null,
      robots: null,
      canonical: null,
      metaable_type: this.model.metaable_type,
      metaable_id: this.model.metaable_id,
      title: null
    });
    this.name = this.model.name;
  }


  constructor(
    service: MetaDataService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

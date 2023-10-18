import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {WidgetMeta} from '../widget.meta';
import {WidgetService} from '../widget.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-block-type-create',
  templateUrl: './widget-create.component.html',
  styleUrls: ['./widget-create.component.css'],
  providers: [WidgetService]
})
export class WidgetCreateComponent extends AbstractModalComponent<WidgetMeta> {
  menuForm: FormGroup;
  style: any;
  html: any;
  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null,[Validators.required, Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s][a-zA-Z0-9~`!@#$%^&*()+=?:;"\'_ÂÀÁÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ ]*$')]),
      description: new FormControl(null),
      html: new FormControl(null, Validators.required),
      js: new FormControl(null),
      css: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
      FieldForm.createTextArea('Mô tả', 'description', 'Nhập mô tả'),
      FieldForm.createHtmlInput('HTML *', 'html', {height: '200px'}),
      FieldForm.createTextArea('CSS', 'css', 'CSS'),
      FieldForm.createTextArea('JS', 'js', 'JS'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: WidgetService,
    modal: BsModalRef,
    builder: FormBuilder,
    private builders: FormBuilder,
  ) {
    super(service, modal, builder);
    this.menuForm = this.builders.group({
      name: new FormControl(null, Validators.required),
      type: new FormControl(null, Validators.required),
      option: new FormControl(null)
    });
  }

  view() {
    this.html = (this.formGroup.controls['css'].value !== null ? '<style>' + this.formGroup.controls['css'].value + '</style>' : '')
      + (this.formGroup.controls['js'].value !== null ? '<script type="application/javascript">' + this.formGroup.controls['js'].value + '</script>' : '')
      + this.formGroup.controls['html'].value;
  }
}

import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {WidgetMeta} from '../widget.meta';
import {WidgetService} from '../widget.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-block-type-edit',
  templateUrl: './widget-edit.component.html',
  styleUrls: ['./widget-edit.component.css'],
  providers: [WidgetService]
})
export class WidgetEditComponent extends AbstractModalComponent<WidgetMeta> {
  ckEditorConfig: any = {
    height: '350px'
  };
  html: any;

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      description: new FormControl(null),
      html: new FormControl(null, Validators.required),
      js: new FormControl(null),
      css: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
      FieldForm.createTextArea('Mô tả', 'description', 'Mô tả ảnh'),
      FieldForm.createHtmlInput('HTML *', 'html', {height: '200px'}),
      FieldForm.createTextArea('CSS', 'css', 'CSS'),
      FieldForm.createTextArea('JS', 'js', 'JS'),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      name: this.model.name,
      description: this.model.description,
      html: this.model.html,
      js: this.model.js,
      css: this.model.css,
    });
  }

  edit() {
    super.edit();
  }

  constructor(
    service: WidgetService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  view() {
    this.html = '<style>' + this.formGroup.controls['css'].value + '</style>' +
      '<script type="application/javascript">' + this.formGroup.controls['js'].value + '</script>'
      + this.formGroup.controls['html'].value;
  }
}

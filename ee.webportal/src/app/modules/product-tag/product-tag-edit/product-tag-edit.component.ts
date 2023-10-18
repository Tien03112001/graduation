import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ProductTagMeta} from '../product-tag.meta';
import {ProductTagService} from '../product-tag.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-post-category-edit',
  templateUrl: './product-tag-edit.component.html',
  styleUrls: ['./product-tag-edit.component.css'],
  providers: [ProductTagService]
})
export class ProductTagEditComponent extends AbstractModalComponent<ProductTagMeta> {


  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      summary: new FormControl(null)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
      FieldForm.createTextArea('Tóm tắt', 'summary', 'Nhập tóm tắt'),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      name: this.model.name,
      summary: this.model.summary,
    });
  }

  constructor(
    service: ProductTagService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

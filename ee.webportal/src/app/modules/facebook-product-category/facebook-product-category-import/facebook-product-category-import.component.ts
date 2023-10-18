import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FacebookProductCategoryService} from '../facebook-product-category.service';
import {FacebookProductCategoryMeta} from '../facebook-product-category.meta';
import { AbstractModalComponent } from '../../../core/crud';
import { FieldForm } from '../../../core/common';


@Component({
  selector: 'app-facebook-product-category-import',
  templateUrl: './facebook-product-category-import.component.html',
  styleUrls: ['./facebook-product-category-import.component.css'],
  providers: [FacebookProductCategoryService]
})
export class FacebookProductCategoryImportComponent extends AbstractModalComponent<FacebookProductCategoryMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      file: new FormControl(null, Validators.required)
    });
  }

  onFileUploadChange(event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      this.formGroup.controls['file'].setValue(input.files[0]);
    }
  }

  initFieldForm(): FieldForm[] {
    return [];
  }

  loaded(): void {
  }

  constructor(
    service: FacebookProductCategoryService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  import() {
    this.service.import(this.formGroup.get('file').value,{}).subscribe(res => {
      this.service.toastSuccessfully('Nhập file', 'Thành công');
      this.close({});
    },() => this.service.toastFailedCreated());;
  }
}

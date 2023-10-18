import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {BannerMeta} from '../banner.meta';
import {BannerService} from '../banner.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-banner-edit',
  templateUrl: './banner-edit.component.html',
  styleUrls: ['./banner-edit.component.css'],
  providers: [BannerService]
})
export class BannerEditComponent extends AbstractModalComponent<BannerMeta> {
  image: any;

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      summary: new FormControl(null),
      image: new FormControl(null, Validators.required),
      href: new FormControl(null),
      group_id: new FormControl()
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
      FieldForm.createTextArea('Mô tả', 'summary', 'Nhập mô tả'),
      FieldForm.createTextInput('Href', 'href', 'Nhập href'),
      FieldForm.createFileInput('Chọn ảnh đại diện *', 'image','Chọn ảnh', this.onImageUploadChange),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      name: this.model.name,
      alt: this.model.alt,
      image: this.model.image,
      href: this.model.href,
      group_id: this.model.group_id
    });
  }

  constructor(
    service: BannerService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }
}

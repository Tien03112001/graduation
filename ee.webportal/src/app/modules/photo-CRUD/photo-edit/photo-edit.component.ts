import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PhotoMeta} from '../photo.meta';
import {PhotoService} from '../photo.service';
import {FieldForm} from '../../../core/common';


@Component({
  selector: 'app-photo-edit',
  templateUrl: './photo-edit.component.html',
  styleUrls: ['./photo-edit.component.css'],
  providers: [PhotoService]
})
export class PhotoEditComponent extends AbstractModalComponent<PhotoMeta> {

  image: any;

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      path: new FormControl(null, Validators.required),
      alt: new FormControl(null)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createFileInput('Chọn ảnh*', 'image', 'Chọn ảnh', this.onImageUploadChange),
      FieldForm.createTextInput('Mô tả ảnh', 'alt', 'Nhập mô tả')
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      path: this.model.path,
      alt: this.model.alt
    });
  }


  constructor(
    service: PhotoService,
    modal: BsModalRef,
    builder: FormBuilder,
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

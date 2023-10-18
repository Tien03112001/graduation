import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {FieldForm} from '../../../core/common';
import {GalleryImageMeta} from '../gallery.meta';
import {GalleryImageService} from '../gallery.service';

@Component({
  selector: 'app-gallery-edit',
  templateUrl: './gallery-edit.component.html',
  styleUrls: ['./gallery-edit.component.css'],
  providers: [GalleryImageService]
})
export class GalleryImageEditComponent extends AbstractModalComponent<GalleryImageMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      gallery_id: new FormControl(null, Validators.required),
      image: new FormControl(null,Validators.required),
      alt: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createFileInput('Ảnh *', 'image', 'Chọn ảnh', this.onImageUploadChange),
      FieldForm.createTextArea('Mô tả', 'alt', 'Nhập mô tả'),
    ];
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }

  loaded(): void {
  }

  constructor(
    service: GalleryImageService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

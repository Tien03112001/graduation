import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {TitleService} from '../../../core/services';
import {FieldForm} from '../../../core/common';
import {GalleryMeta} from '../../gallery/gallery.meta';
import {GalleryImageMeta} from '../gallery.meta';
import {GalleryImageService} from '../gallery.service';

@Component({
  selector: 'app-gallery-create',
  templateUrl: './gallery-create.component.html',
  styleUrls: ['./gallery-create.component.css'],
  providers: [GalleryImageService]
})
export class GalleryImageCreateComponent extends AbstractModalComponent<GalleryImageMeta> {
  gallery: GalleryMeta;

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      gallery_id: new FormControl(null, Validators.required),
      image: new FormControl(null, Validators.required),
      alt: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createFileInput('Ảnh *', 'image', 'Chọn ảnh', this.onImageUploadChange),
      FieldForm.createTextArea('Mô tả', 'alt', 'Nhập mô tả'),
    ];
  }

  loaded(): void {
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }

  constructor(
    service: GalleryImageService,
    modal: BsModalRef,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }
}

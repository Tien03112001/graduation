import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {TitleService} from '../../../core/services';
import {FieldForm} from '../../../core/common';
import {GalleryMeta} from '../../gallery/gallery.meta';
import {GalleryService} from '../gallery.service';


@Component({
  selector: 'app-gallery-create',
  templateUrl: './gallery-create.component.html',
  styleUrls: ['./gallery-create.component.css'],
  providers: [GalleryService]
})
export class GalleryCreateComponent extends AbstractModalComponent<GalleryMeta> {
  gallery: GalleryMeta;

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      galleryable_id: new FormControl(null),
      galleryable_type: new FormControl(null),
      images_count: new FormControl(0),
      name: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextArea('Tên*', 'name', 'Nhập mô tả'),
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
    service: GalleryService,
    modal: BsModalRef,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }
}

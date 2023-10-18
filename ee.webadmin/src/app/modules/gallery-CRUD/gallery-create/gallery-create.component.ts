import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {GalleryMeta} from '../gallery.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {GalleryService} from '../gallery.service';
import {TitleService} from '../../../core/services';
import {FieldForm} from '../../../core/common';

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
      name: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s].*$')]),
      description: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
      FieldForm.createTextArea('Mô tả', 'description', 'Nhập mô tả'),
    ];
  }

  loaded(): void {
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

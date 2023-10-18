import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {GalleryMeta} from '../gallery.meta';
import {GalleryService} from '../gallery.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-gallery-edit',
  templateUrl: './gallery-edit.component.html',
  styleUrls: ['./gallery-edit.component.css'],
  providers: [GalleryService]
})
export class GalleryEditComponent extends AbstractModalComponent<GalleryMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
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
    this.formGroup.setValue({
      name: this.model.name,
      description: this.model.description,
    });
  }

  constructor(
    service: GalleryService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

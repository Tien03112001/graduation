import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PhotoMeta} from '../photo.meta';
import {PhotoService} from '../photo.service';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-photo-create',
  templateUrl: './photo-create.component.html',
  styleUrls: ['./photo-create.component.css'],
  providers: [PhotoService]
})
export class PhotoCreateComponent extends AbstractModalComponent<PhotoMeta> {
  img: any = null;
  img_list: any = [];

  onDestroy(): void {
  }

  onInit(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      image: new FormControl(null, Validators.required),
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
  }

  constructor(
    service: PhotoService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  urls = new Array<string>();

  detectFiles(event) {
    this.urls = [];
    let files = event.target.files;
    if (files) {
      this.formGroup.controls['file_count'].setValue(files.length);
      for (let file of files) {
        let reader = new FileReader();
        reader.onload = (e: any) => {
          this.urls.push(e.target.result);
          this.img_list.push(file);
        };
        reader.readAsDataURL(file);
      }
    }
  }

  createOb() {
    let item: PhotoMeta = ObjectUtil.ignoreNullValue(ObjectUtil.combineValue(this.model, this.formGroup.value));
    for (let i = 0; i < this.img_list.length; i++) {
      item['img' + i] = this.img_list[i];
    }
    item['img_count'] = this.img_list.length;
    this.service.storeWithImage(item).subscribe((res: PhotoMeta) => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }
}

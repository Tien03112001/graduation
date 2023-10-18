import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {BannerMeta} from '../banner.meta';
import {BannerService} from '../banner.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-banner-create',
  templateUrl: './banner-create.component.html',
  styleUrls: ['./banner-create.component.css'],
  providers: [BannerService]
})
export class BannerCreateComponent extends AbstractModalComponent<BannerMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }


  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s][a-zA-Z0-9~`!@#$%^&*()+=?:;"\'_ÂÀÁÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ ]*$')]),
      summary: new FormControl(null, Validators.maxLength(255)),
      image: new FormControl(null, Validators.required),
      href: new FormControl(null, Validators.maxLength(255)),
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

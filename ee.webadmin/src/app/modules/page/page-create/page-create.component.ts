import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {PageMeta} from '../page.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PageService} from '../page.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-page-create',
  templateUrl: './page-create.component.html',
  styleUrls: ['./page-create.component.css'],
  providers: [PageService]
})
export class PageCreateComponent extends AbstractModalComponent<PageMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s][a-zA-Z0-9~`!@#$%^&*()+=?:;"\'_ÂÀÁÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ ]*$')]),
      title: new FormControl(null),
      content: new FormControl(null, Validators.required),
      slug: new FormControl(null, [Validators.required, Validators.pattern('^[a-z0-9]+(?:-[a-z0-9]+)*$')])
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên trang *', 'name', 'Nhập tên trang'),
      FieldForm.createTextInput('Đường dẫn *', 'slug', 'Nhập đường dẫn'),
      FieldForm.createTextInput('Tiêu đề', 'title', 'Nhập tiêu đề'),
      FieldForm.createHtmlInput('Nội dung *', 'content', 'Nhập nội dung'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: PageService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

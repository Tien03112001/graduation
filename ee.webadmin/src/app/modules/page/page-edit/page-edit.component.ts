import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PageMeta} from '../page.meta';
import {PageService} from '../page.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-page-edit',
  templateUrl: './page-edit.component.html',
  styleUrls: ['./page-edit.component.css'],
  providers: [PageService]
})
export class PageEditComponent extends AbstractModalComponent<PageMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null,[Validators.required, Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s][a-zA-Z0-9~`!@#$%^&*()+=?:;"\'_ÂÀÁÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ ]*$')]),
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
    this.formGroup.setValue({
      name: this.model.name,
      title: this.model.article.title,
      content: this.model.article.content,
      slug: this.model.slug
    });
  }

  constructor(
    service: PageService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

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
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên trang *', 'name', 'Nhập tên trang'),
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

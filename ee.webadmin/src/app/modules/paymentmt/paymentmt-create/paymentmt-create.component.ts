import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {PaymentmtMeta} from '../paymentmt.meta';
import {PaymentmtService} from '../paymentmt.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-paymentmt-create',
  templateUrl: './paymentmt-create.component.html',
  styleUrls: ['./paymentmt-create.component.css'],
  providers: [PaymentmtService]
})
export class PaymentmtCreateComponent extends AbstractModalComponent<PaymentmtMeta> {
  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-zA-Z\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$')]),
      description: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập ký tự'),
      FieldForm.createTextInput('Mô tả', 'description', 'Nhập ký tự')
    ];
  }

  loaded(): void {
  }

  constructor(
    service: PaymentmtService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

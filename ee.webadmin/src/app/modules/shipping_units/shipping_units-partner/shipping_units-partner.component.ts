import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {AbstractModalComponent} from '../../../core/crud';
import {ShippingUnitsMeta} from '../shipping_units.meta';
import {ShippingUnitsService} from '../shipping_units.service';
import {FieldForm} from '../../../core/common';
import {PaymentMethodMeta} from '../../payment-method/payment-method.meta';
import {PaymentMethodService} from '../../payment-method/payment-method.service';
import {ObjectUtil} from '../../../core/utils';

@Component({
  selector: 'app-shipping_units-partner',
  templateUrl: './shipping_units-partner.component.html',
  styleUrls: ['./shipping_units-partner.component.css'],
  providers: [ShippingUnitsService]
})
export class Shipping_unitsPartnerComponent extends AbstractModalComponent<ShippingUnitsMeta> {
  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      store: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*[a-z0-9A-Z!@#\$%\^\&*\,\?)\(+=._-\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-z0-9A-Z!@#\$%\^\&*\,\?)\(+=._-\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$')]),
      service: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*[a-z0-9A-Z!@#\$%\^\&*\,\?)\(+=._-\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ]+)[a-z0-9A-Z!@#\$%\^\&*\,\?)\(+=._-\đàáảãạăằắẳẵặâầấẩẫậèéẻẽẹêềếểễệìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵĐÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÈÉẺẼẸÊỀẾỂỄỆÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴ ]*$')]),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Kho', 'store', 'Nhập tên kho'),
      FieldForm.createTextInput('Dịch vụ vận chuyển', 'service', 'Nhập tên dịch vụ vận chuyển'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ShippingUnitsService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  createUnitPartner() {
    let item= this.prepareParams();
    (<ShippingUnitsService>this.service).createUnitPartner(item).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedEdited());
  }
}

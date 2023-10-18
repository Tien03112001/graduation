import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ShippingFeeTableMeta} from '../shipping-fee-table.meta';
import {ShippingFeeTableService} from '../shipping-fee-table.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-shipping-fee-table-edit',
  templateUrl: './shipping-fee-table-edit.component.html',
  styleUrls: ['./shipping-fee-table-edit.component.css'],
  providers: [ShippingFeeTableService]
})
export class ShippingFeeTableEditComponent extends AbstractModalComponent<ShippingFeeTableMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };


  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      fee: new FormControl(null)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createNumberInput('Tiền ship *', 'fee', 'Nhập Tiền ship'),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: ShippingFeeTableService,
    modal: BsModalRef,
    builder: FormBuilder,
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

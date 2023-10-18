import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {SaleOrderService} from '../sale-order.service';
import {SaleOrderMeta} from '../sale-order.meta';
import {AbstractModalComponent, FieldForm} from '../../../core';


@Component({
  selector: 'app-order-swap',
  templateUrl: './sale-order-swap.component.html',
  styleUrls: ['./sale-order-swap.component.css'],
  providers: [SaleOrderService]
})
export class SaleOrderSwapComponent extends AbstractModalComponent<SaleOrderMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      from_details_text: new FormControl(null, Validators.required),
      to_details_text: new FormControl(null, Validators.required),
      ship_fee: new FormControl(0, [Validators.required]),
      note: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextArea('Sản phẩm cần đổi', 'from_details_text', 'Bắt buộc. Ví dụ: 4200909 L 199 2' ,5),
      FieldForm.createTextArea('Sản phẩm được đổi', 'to_details_text', 'Bắt buộc. Ví dụ: 4200909 L 199 2' ,5),
      FieldForm.createTextArea('Ghi chú', 'note', 'Nhập kí tự' ,5),
      FieldForm.createNumberInput('Phí ship *', 'ship_fee', 'Nhập số'),
    ];
  }

  loaded(): void {
    this.service.setURLRestAPI(`sale_orders/${this.model.id}/createSwappingOrder`);
    this.formGroup.controls['from_details_text'].setValue(this.model.details_text);
  }

  constructor(
    service: SaleOrderService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

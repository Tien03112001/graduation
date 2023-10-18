import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {SaleOrderService} from '../sale-order.service';
import {SaleOrderMeta} from '../sale-order.meta';
import {AbstractModalComponent, FieldForm} from '../../../core';


@Component({
  selector: 'app-order-refund-edit',
  templateUrl: './sale-order-refund.component.html',
  styleUrls: ['./sale-order-refund.component.css'],
  providers: [SaleOrderService]
})
export class SaleOrderRefundComponent extends AbstractModalComponent<SaleOrderMeta> {
  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      refund: new FormControl(null, Validators.min(0)),
      note: new FormControl(null, Validators.maxLength(255)),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createNumberInput('Số tiền cần hoàn lại', 'refund', 'Nhập số'),
      FieldForm.createTextArea('Ghi chú', 'note', 'Nhập kí tự' ,5),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: SaleOrderService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  refundOrder() {
    let item: SaleOrderMeta = this.prepareParams();
    (<SaleOrderService>this.service).refund(item).subscribe(res => {
      this.service.toastSuccessfully("Hoàn tiền", "Thành công");
      this.close(res);
    }, () => this.service.toastFailed("Hoàn tiền", "Thất bại"));
  }
}

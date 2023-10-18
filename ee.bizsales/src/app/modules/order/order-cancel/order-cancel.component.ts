import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {OrderMeta} from '../order.meta';
import {OrderService} from '../order.service';
import {AbstractModalComponent, FieldForm} from '../../../core';

@Component({
  selector: 'app-order-cancel',
  templateUrl: './order-cancel.component.html',
  styleUrls: ['./order-cancel.component.css'],
  providers: [OrderService]
})
export class OrderCancelComponent extends AbstractModalComponent<OrderMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      note: new FormControl(null, Validators.maxLength(255)),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextArea('Lý do hủy đơn', 'note', 'Nhập kí tự' ,5),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: OrderService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  cancel() {
    let item: OrderMeta = this.prepareParams();
    (<OrderService>this.service).cancel(item).subscribe(res => {
      this.service.toastSuccessfully('Hủy', 'Thành công');
      this.close(res);
    });
  }
}

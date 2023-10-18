import {Component} from '@angular/core';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import { AbstractModalComponent } from '../../../core';
import {SaleOrderShippingMeta} from '../sale-order-shipping.meta';
import {SaleOrderShippingService} from '../sale-order-shipping.service';

@Component({
  selector: 'app-order-shipping-info',
  templateUrl: './sale-order-shipping-info.component.html',
  styleUrls: ['./sale-order-shipping-info.component.css'],
  providers: [SaleOrderShippingService]
})
export class SaleOrderShippingInfoComponent extends AbstractModalComponent<SaleOrderShippingMeta> {

  info: any;

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({});
  }


  loaded(): void {
    this.service.loadByID(this.model.id).subscribe(
      value => this.info = JSON.stringify(value, null, 2),
      () => this.info = 'Không tìm thấy'
    );
  }

  constructor(
    service: SaleOrderShippingService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  remove() {
    this.service.destroy(this.model.id).subscribe(val=>{
      this.close({})
    })
  }
}

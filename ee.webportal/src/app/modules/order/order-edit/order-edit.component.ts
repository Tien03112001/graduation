import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {OrderMeta} from '../order.meta';
import {OrderService} from '../order.service';
import {FieldForm, ModalResult} from '../../../core/common';
import {OrderProductMeta} from '../../order-product/order-product.meta';
import {ProductVariantMeta} from '../../product-variant/product-variant.meta';
import {ProductVariantListComponent} from '../../product-variant/product-variant-list/product-variant-list.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {ObjectUtil} from '../../../core/utils';
import {ProductService} from '../../product/product.service';

@Component({
  selector: 'app-order-edit',
  templateUrl: './order-edit.component.html',
  styleUrls: ['./order-edit.component.css'],
  providers: [OrderService]
})
export class OrderEditComponent extends AbstractModalComponent<OrderMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }
  loadAllType() {
    return [{
      id: 0,
      name: 'PENDING',
    }, {
      id: 1,
      name: 'Xác thực',
    }, {
      id: 2,
      name: 'Chuẩn bị hàng',
    }, {
      id: 3,
      name: 'Đang giao',
    }, {
      id: 4,
      name: 'Hoàn thành',
    }, {
      id: 5,
      name: 'Hoàn về',
    }, ];
  }
  buildForm(): FormGroup {
    return this.formBuilder.group({
      code: new FormControl(null,),
      customer_name: new FormControl(null,),
      customer_phone: new FormControl(null,),
      customer_address: new FormControl(null,),
      total_amount: new FormControl(null,),
      payment_type: new FormControl(null,),
      order_status: new FormControl(null,),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Mã giảm giá *', 'code', 'Mã Đơn '),
      FieldForm.createTextInput('Ten KH', 'customer_name', 'Ten KH'),
      FieldForm.createTextInput('SĐT', 'customer_phone', 'SĐT'),
      FieldForm.createTextInput('Địa chỉ', 'customer_address', 'Địa chỉ'),
      FieldForm.createTextInput('Địa chỉ', 'total_amount', 'Tổng tiền'),
      FieldForm.createTextInput('Phương thức TT', 'payment_type', 'Phương thức TT'),
      FieldForm.createSelect('Trạng thái đơn', 'order_status', 'Trạng thái đơn',this.loadAllType()),
    ];
  }
  loaded(): void {
    let param = {
      order_id: this.model.id,
    };
    this.service.loadByParams(param).subscribe((res: OrderMeta[]) => {
        this.list = res;
      console.log(res);
      }, () => {
        this.list = [];
      }
    );
  }
  editOrder() {
    let item: OrderMeta = this.prepareParams();
    console.log(this.prepareParams());
    this.orderService.update(item).subscribe(res => {
      this.service.toastSuccessfullyEdited();
      this.close(ObjectUtil.combineValue(this.model, res));
    }, () => this.service.toastFailedEdited());
  }


  constructor(
    service: OrderService,
    modal: BsModalRef,
    builder: FormBuilder,
    private orderService: OrderService,
  ) {
    super(service, modal, builder);
  }
}

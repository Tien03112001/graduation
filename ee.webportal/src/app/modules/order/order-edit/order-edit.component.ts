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

  loadAllType(status : any) {
    if (status == 'PENDING') {
      return [ {
        id: 2,
        name: 'Chuẩn bị hàng',
      },
        {
          id: 6,
          name: 'Hủy đơn',
        }, ];
    }
    if (status == 'Chuẩn bị hàng') {
      return [ {
        id: 3,
        name: 'Đang giao',
      }, {
        id: 7,
        name: 'Hoàn về kho',
      }, ];
    }
    if (status === 'Đang giao') {
      return [{
        id: 4,
        name: 'Hoàn thành',
      }, {
        id: 5,
        name: 'Hoàn về',
      }
      ];
    }
    if (status == 'Hoàn về') {
      return [,
        {
          id: 7,
          name: 'Hoàn về kho',
        },
      ];
    }
    if (status == 'Hoàn thành') {
      return [];
    }
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
  loaded(): void {
    this.formGroup.setValue({
      code: this.model.code,
      customer_name: this.model.customer_name,
      customer_phone: this.model.customer_phone,
      customer_address: this.model.customer_address,
      total_amount: this.model.total_amount,
      payment_type: this.model.payment_type,
      order_status: this.model.order_status,
    });
    let fileForm1 = FieldForm.createSelect('Trạng thái đơn', 'order_status', 'Trạng thái đơn', this.loadAllType(this.model.order_status));
    this.fields.push(fileForm1);

    let param = {
      order_id: this.model.id,
    };
    this.service.loadByParams(param).subscribe((res: OrderMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }
  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Mã giảm giá *', 'code', 'Mã Đơn '),
      FieldForm.createTextInput('Ten KH', 'customer_name', 'Ten KH'),
      FieldForm.createTextInput('SĐT', 'customer_phone', 'SĐT'),
      FieldForm.createTextInput('Địa chỉ', 'customer_address', 'Địa chỉ'),
      FieldForm.createTextInput('Tổng tiền', 'total_amount', 'Tổng tiền'),
      FieldForm.createTextInput('Phương thức TT', 'payment_type', 'Phương thức TT'),
      // FieldForm.createSelect('Trạng thái đơn', 'order_status', 'Trạng thái đơn',this.loadAllType()),
    ];
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

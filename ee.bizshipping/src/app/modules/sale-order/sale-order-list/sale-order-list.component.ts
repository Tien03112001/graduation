import { Component } from '@angular/core';
import { BsModalService, ModalOptions } from 'ngx-bootstrap';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { SaleOrderMeta } from '../sale-order.meta';
import { SaleOrderService } from '../sale-order.service';
import { DefinitionService } from '../../definition/definition.service';
import { SaleOrderShippingMeta } from '../../sale-order-shipping/sale-order-shipping.meta';
import { SaleOrderShippingInfoComponent } from '../../sale-order-shipping/sale-order-shipping-info/sale-order-shipping-info.component';
import { SaleOrderShippingCreateComponent } from '../../sale-order-shipping/sale-order-shipping-create/sale-order-shipping-create.component';
import { SaleOrderShippingService } from '../../sale-order-shipping/sale-order-shipping.service';
import { AbstractCRUDComponent, AbstractModalComponent, FieldForm, ModalResult, ObjectUtil, TitleService } from '../../../core';
import { SaleOrderNoteComponent } from '../sale-order-note/sale-order-note.component';
import { SaleOrderRefundComponent } from '../sale-order-refund/sale-order-refund.component';

@Component({
  selector: 'app-order-list',
  templateUrl: './sale-order-list.component.html',
  styleUrls: ['./sale-order-list.component.css'],
  providers: [SaleOrderService, DefinitionService, SaleOrderShippingService]
})
export class SaleOrderListComponent extends AbstractCRUDComponent<SaleOrderMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Danh sách đơn hàng';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return { class: 'modal-huge', ignoreBackdropClick: true, backdrop: 'static', keyboard: false };
  }

  getEditModalComponentOptions(): ModalOptions {
    return { class: 'modal-huge' };
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      status: new FormControl(null),
      shipping_status: new FormControl(null),
      shipping_code: new FormControl(null),
      customer_name: new FormControl(null),
      customer_phone: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createSelect('Trạng thái đơn hàng', 'status', 'Chọn một', 'loadAllOrderStatus','col-md-6', 'name', 'name'),
      FieldForm.createSelect('Trạng thái vận đơn', 'shipping_status', 'Chọn một', 'loadAllShippingStatus', 'col-md-6', 'name', 'name'),
      FieldForm.createTextInput('Tên khách hàng', 'customer_name', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createTextInput('Số điện thoại', 'customer_phone', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createTextInput('Mã vận đơn', 'shipping_code', 'Nhập từ khóa', 'col-md-6'),
    ];
  }

  loadAllShippingStatus() {
    return this.defService.loadAllSaleOrderShippingStatus();
  }

  loadAllOrderStatus() {
    return this.defService.loadAllSaleOrderStatus();
  }

  initNewModel(): SaleOrderMeta {
    return null;
  }

  constructor(
    service: SaleOrderService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private defService: DefinitionService,
    private saleOrderShippingService: SaleOrderShippingService
  ) {
    super(service, modal, builder);
  }

  printShippingBill(item: SaleOrderMeta) {
    this.saleOrderShippingService.printBill(item.shipping.id).subscribe(res => {
      this.service.toastSuccessfully('In vận đơn');
      if (res['link']) {
        window.open(res['link']);
      }
      if (res['src']) {
        var win = window.open('', 'In đơn hàng', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=200,top=' + (screen.height - 400) + ',left=' + (screen.width - 840));
        win.document.body.innerHTML = res['src'];
      }
      this.load();
    }, () => this.service.toastFailed('In vận đơn'));
  }

  infoShipping(shipping: SaleOrderShippingMeta, item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderShippingInfoComponent, config);
    let modal: AbstractModalComponent<SaleOrderShippingMeta> = <AbstractModalComponent<SaleOrderShippingMeta>>modalRef.content;
    modal.setModel(shipping);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderShippingMeta>) => {
      if (result.success) {
        this.list[i].shipping = null;
        this.list[i].status = 'Chuẩn bị hàng';
      }
      sub.unsubscribe();
    });
  }

  cancel(item: SaleOrderMeta, index: number) {
    let confirmed = window.confirm('Xác nhận hủy vận đơn?');
    if (confirmed) {
      (<SaleOrderService>this.service).cancel(item).subscribe(val => {
        this.list[index] = val;
        this.service.toastSuccessfully('Hủy đơn');
        this.load();
      }, error => {
        this.service.toastFailed('Hủy đơn');
      });
    }
  }

  reUpdate(item: SaleOrderMeta, index: number) {
    let confirmed = window.confirm('Xác nhận cập nhật lại?');
    if (confirmed) {
      (<SaleOrderService>this.service).reUpdate(item).subscribe(val => {
        this.list[index] = val;
        this.service.toastSuccessfully('Cập nhật lại');
        this.load();
      }, error => {
        this.service.toastFailed('Cập nhật lại');
      });
    }
  }

  done(item: SaleOrderMeta, index: number) {
    let confirmed = window.confirm('Xác nhận đã giao đơn hàng?');
    if (confirmed) {
      (<SaleOrderService>this.service).done(item).subscribe(val => {
        this.list[index] = val;
        this.service.toastSuccessfully('Hoàn thành');
        this.load();
      }, error => {
        this.service.toastFailed('Hoàn thành');
      });
    }
  }

  return(item: SaleOrderMeta, index: number) {
    let confirmed = window.confirm('Xác nhận hoàn hàng?');
    if (confirmed) {
      (<SaleOrderService>this.service).return(item).subscribe(val => {
        this.list[index] = val;
        this.service.toastSuccessfully('Nhận hoàn');
        this.load();
      }, error => {
        this.service.toastFailed('Nhận hoàn');
      });
    }
  }

  receiveRefund(item: SaleOrderMeta, index: number) {
    let confirmed = window.confirm('Xác nhận đã nhận hoàn?');
    if (confirmed) {
      (<SaleOrderService>this.service).receiveRefund(item).subscribe(val => {
        this.list[index] = val;
        this.service.toastSuccessfully('Nhận hoàn');
        this.load();
      }, error => {
        this.service.toastFailed('Nhận hoàn');
      });
    }
  }

  refund(item: SaleOrderMeta, index: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderRefundComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  recreate(shipping: SaleOrderShippingMeta, item: SaleOrderMeta, i: number) {
    let confirmed = window.confirm('Xác nhận tạo lại mã vận đơn?');
    if (confirmed) {
      this.saleOrderShippingService.recreate(item.shipping.id).subscribe(val => {
        this.list[i].shipping = val;
        this.service.toastSuccessfully('Tạo lại mã vận đơn');
        this.load();
      }, error => {
        this.service.toastFailed('Tạo lại mã vận đơn');
      });
    }
  }

  note(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderNoteComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  public goToPageNumber() {
    this.nextPage = Math.round(this.nextPage);
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (Math.round(this.nextPage) > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }
}

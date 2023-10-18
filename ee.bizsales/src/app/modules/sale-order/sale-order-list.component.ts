import { Component } from '@angular/core';
import { BsModalService, ModalOptions } from 'ngx-bootstrap';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { SaleOrderMeta } from './sale-order.meta';
import { SaleOrderService } from './sale-order.service';
import { SaleOrderCreateComponent } from './sale-order-create/sale-order-create.component';
import { SaleOrderEditComponent } from './sale-order-edit/sale-order-edit.component';
import { SaleOrderVerifyComponent } from './sale-order-verify/sale-order-verify.component';
import { SaleOrderNoteComponent } from './sale-order-note/sale-order-note.component';
import { SaleOrderSwapComponent } from './sale-order-swap/sale-order-swap.component';
import { SaleOrderShippingMeta } from '../sale-order-shipping/sale-order-shipping.meta';
import { SaleOrderShippingInfoComponent } from '../sale-order-shipping/sale-order-shipping-info/sale-order-shipping-info.component';
import { AbstractCRUDComponent, AbstractModalComponent, FieldForm, ModalResult, ObjectUtil, TitleService } from '../../core';
import { ChannelService } from '../channel/channel.service';
import { SaleOrderStatusService } from '../sale-order-status/sale-order-status.service';
import { SaleOrderDraftVerifyComponent } from './sale-order-draft-verify/sale-order-draft-verify.component';
import { SaleOrderDraftEditComponent } from './sale-order-draft-edit/sale-order-draft-edit.component';

@Component({
  selector: 'app-order-list',
  templateUrl: './sale-order-list.component.html',
  styleUrls: ['./sale-order-list.component.css'],
  providers: [SaleOrderService, ChannelService, SaleOrderStatusService],
})
export class SaleOrderListComponent extends AbstractCRUDComponent<SaleOrderMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Đơn hàng của tôi';
  }

  getCreateModalComponent(): any {
    return SaleOrderCreateComponent;
  }

  getEditModalComponent(): any {
    return SaleOrderEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return { class: 'modal-huge', ignoreBackdropClick: true, backdrop: 'static', keyboard: false };
  }

  getEditModalComponentOptions(): ModalOptions {
    return { class: 'modal-huge', backdrop: 'static', keyboard: false };
  }

  loadAllChannel() {
    return this.channelService.loadAll();
  }

  loadAllStatus() {
    return this.statusService.loadAll();
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.maxLength(255)),
      status: new FormControl(null),
      code: new FormControl(null),
      customer_phone: new FormControl(null, [Validators.pattern('^(?=.*[0-9]+)[0-9]*$'), Validators.maxLength(10)]),
      created_date: new FormControl(null),
      channel: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên khách hàng', 'search', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createSelect('Kênh', 'channel', 'Chọn một', 'loadAllChannel', 'col-md-6', 'name', 'value'),
      FieldForm.createNumberInput('Tìm kiếm theo số điện thoại', 'customer_phone', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createSelect('Trạng thái', 'status', 'Chọn một', 'loadAllStatus', 'col-md-6', 'name', 'value'),
      FieldForm.createDateInput('Ngày tạo', 'created_date', 'Chọn ngày', 'col-md-6'),
    ];
  }

  initNewModel(): SaleOrderMeta {
    return new SaleOrderMeta();
  }

  constructor(
    service: SaleOrderService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private channelService: ChannelService,
    private statusService: SaleOrderStatusService
  ) {
    super(service, modal, builder);
  }

  public createOrder() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  public editOrder(item, i) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  verify(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderVerifyComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    let model: SaleOrderMeta = ObjectUtil.mergeValue(item, { status: SaleOrderMeta.$TRANG_THAI_XAC_THUC });
    modal.setModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  draftedit(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderDraftEditComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  draftverify(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderDraftVerifyComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  cancel(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderNoteComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        let sub2 = (<SaleOrderService>this.service).cancel(result.data).subscribe(res => {
          this.list[i] = ObjectUtil.mergeValue(item, res);
          this.service.toastSuccessfully('Hủy đơn hàng');
          sub2.unsubscribe();
        });
      }
      sub.unsubscribe();
    });
  }


  confirm(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderNoteComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    let model: SaleOrderMeta = ObjectUtil.mergeValue(item, { status: SaleOrderMeta.$TRANG_THAI_CHUAN_BI_HANG });
    modal.setModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        let sub2 = (<SaleOrderService>this.service).confirm(result.data).subscribe(res => {
          this.list[i] = ObjectUtil.mergeValue(item, res);
          this.service.toastSuccessfully('Xác nhận đơn hàng');
          sub2.unsubscribe();
        });
      }
      sub.unsubscribe();
    });
  }


  complain(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderNoteComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    let model: SaleOrderMeta = ObjectUtil.mergeValue(item, { status: SaleOrderMeta.$TRANG_THAI_KHIEU_NAI });
    modal.setModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        let sub2 = (<SaleOrderService>this.service).complain(result.data).subscribe(res => {
          this.list[i] = ObjectUtil.mergeValue(item, res);
          this.service.toastSuccessfully('Khiếu nại đơn hàng');
          sub2.unsubscribe();
        });
      }
      sub.unsubscribe();
    });
  }

  swap(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderNoteComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    let model: SaleOrderMeta = ObjectUtil.mergeValue(item, { status: SaleOrderMeta.$TRANG_THAI_DOI_HANG });
    modal.setModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        let sub2 = (<SaleOrderService>this.service).swap(result.data).subscribe(res => {
          this.list[i] = ObjectUtil.mergeValue(item, res);
          this.service.toastSuccessfully('Đổi hàng');
          sub2.unsubscribe();
        });
      }
      sub.unsubscribe();
    });
  }

  refund(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderNoteComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    let model: SaleOrderMeta = ObjectUtil.mergeValue(item, { status: SaleOrderMeta.$TRANG_THAI_HOAN_VE });
    modal.setModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        let sub2 = (<SaleOrderService>this.service).refund(result.data).subscribe(res => {
          this.list[i] = ObjectUtil.mergeValue(item, res);
          this.service.toastSuccessfully('Hoàn về đơn hàng');
          sub2.unsubscribe();
        });
      }
      sub.unsubscribe();
    });
  }

  refundAmount(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderNoteComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    let model: SaleOrderMeta = ObjectUtil.mergeValue(item, { status: SaleOrderMeta.$TRANG_THAI_HOAN_TIEN });
    modal.setModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderMeta>) => {
      if (result.success) {
        let sub2 = (<SaleOrderService>this.service).refundAmount(result.data).subscribe(res => {
          this.list[i] = ObjectUtil.mergeValue(item, res);
          this.service.toastSuccessfully('Hoàn tiền đơn hàng');
          sub2.unsubscribe();
        });
      }
      sub.unsubscribe();
    });
  }

  complete(item: SaleOrderMeta, i: number) {
    let sub = (<SaleOrderService>this.service).complete(item).subscribe(res => {
      this.list[i] = res;
      this.service.toastSuccessfully('Hoàn thành ');
      sub.unsubscribe();
    });
  }

  createSwappingOrder(item: SaleOrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderSwapComponent, config);
    let modal: AbstractModalComponent<SaleOrderMeta> = <AbstractModalComponent<SaleOrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.list[i] = ObjectUtil.mergeValue(item, result.data['swapped_order']);
        this.list.unshift(result.data['new_order']);
        this.service.toastSuccessfully('Đổi hàng');
      }
      sub.unsubscribe();
    });
  }

  recreate(item: SaleOrderMeta, i: number) {
    let sub = (<SaleOrderService>this.service).recreate(item).subscribe(res => {
      this.list[i] = ObjectUtil.mergeValue(item, res);
      this.service.toastSuccessfully('Tạo lại đơn hàng');
      sub.unsubscribe();
    });
  }

  infoShipping(shipping: SaleOrderShippingMeta) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderShippingInfoComponent, config);
    let modal: AbstractModalComponent<SaleOrderShippingMeta> = <AbstractModalComponent<SaleOrderShippingMeta>>modalRef.content;
    modal.setModel(shipping);
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

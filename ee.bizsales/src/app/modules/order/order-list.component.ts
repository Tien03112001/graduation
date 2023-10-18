import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {OrderMeta} from './order.meta';
import {OrderService} from './order.service';
import {AbstractCRUDComponent, AbstractModalComponent, FieldForm, ModalResult, ObjectUtil, TitleService} from '../../core';
import { OrderVerifyComponent } from './order-verify/order-verify.component';
import { OrderCancelComponent } from './order-cancel/order-cancel.component';
import { OrderEditComponent } from './order-edit/order-edit.component';
import { SaleOrderStatusService } from '../sale-order-status/sale-order-status.service';

@Component({
  selector: 'app-order-list',
  templateUrl: './order-list.component.html',
  styleUrls: ['./order-list.component.css'],
  providers: [OrderService, SaleOrderStatusService],
})
export class OrderListComponent extends AbstractCRUDComponent<OrderMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Đơn hàng trên web';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return OrderEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-huge', ignoreBackdropClick: true, backdrop: 'static', keyboard: false};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-huge', backdrop: 'static', keyboard: false};
  }

  loadAllStatus() {
    return this.statusService.loadAll();
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.maxLength(255)),
      customer_phone: new FormControl(null, [Validators.pattern('^(?=.*[0-9]+)[0-9]*$'), Validators.maxLength(10)]),
      created_date: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên khách hàng', 'search', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createDateInput('Ngày tạo', 'created_date', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createNumberInput('Tìm kiếm theo số điện thoại', 'customer_phone', 'Nhập từ khóa', 'col-md-6')
    ];
  }

  initNewModel(): OrderMeta {
    return new OrderMeta();
  }


  constructor(
    service: OrderService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private statusService: SaleOrderStatusService
  ) {
    super(service, modal, builder);
  }

  public editOrder(item, i) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<OrderMeta> = <AbstractModalComponent<OrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<OrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  verify(item: OrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(OrderVerifyComponent, config);
    let modal: AbstractModalComponent<OrderMeta> = <AbstractModalComponent<OrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<OrderMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  cancel(item: OrderMeta, i: number) {
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(OrderCancelComponent, config);
    let modal: AbstractModalComponent<OrderMeta> = <AbstractModalComponent<OrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<OrderMeta>) => {
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

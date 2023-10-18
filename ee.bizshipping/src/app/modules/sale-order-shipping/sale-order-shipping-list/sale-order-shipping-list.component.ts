import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {SaleOrderMeta} from '../../sale-order/sale-order.meta';
import {SaleOrderService} from '../../sale-order/sale-order.service';
import {SaleOrderShippingMeta} from '../sale-order-shipping.meta';
import {SaleOrderShippingCreateComponent} from '../sale-order-shipping-create/sale-order-shipping-create.component';
import {SaleOrderShippingService} from '../sale-order-shipping.service';
import {SaleOrderShippingCreateResultComponent} from '../sale-order-shipping-create-result/sale-order-shipping-create-result.component';
import { AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent, AppPagination, FieldForm, ModalResult, ObjectUtil, TitleService } from '../../../core';
import { DefinitionService } from '../../definition/definition.service';

@Component({
  selector: 'app-sale-order-shipping-list',
  templateUrl: './sale-order-shipping-list.component.html',
  styleUrls: ['./sale-order-shipping-list.component.css'],
  providers: [SaleOrderService, SaleOrderShippingService, DefinitionService]
})
export class SaleOrderShippingListComponent extends AbstractCRUDComponent<SaleOrderMeta> {

  statusSelectAll: boolean = false;
  selectors: boolean[];

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Danh sách đơn giao';
  }

  public load() {
    this.statusSelectAll = false;
    let params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, this.searchForm.value, true);
    this.service.loadByPage(params).subscribe(res => {
        this.list = res.data;
        this.pagination.set(res);
        this.selectors = this.list.map(value => false);
      }, () => {
        this.list = [];
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
      }
    );
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-huge', ignoreBackdropClick: true, backdrop: 'static', keyboard: false};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-huge'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      customer_name: new FormControl(null),
      customer_phone: new FormControl(null),
      is_completed: new FormControl(0, Validators.required),
      status: new FormControl('Chuẩn bị hàng', Validators.required),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên khách hàng', 'customer_name', 'Nhập từ khóa', 'col-md-6'),
      FieldForm.createTextInput('Số điện thoại', 'customer_phone', 'Nhập từ khóa', 'col-md-6'),
    ];
  }

  initNewModel(): SaleOrderMeta {
    return null;
  }

  constructor(
    service: SaleOrderService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
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

  ship() {
    let ids: number[] = this.list.filter((val, index) => this.selectors[index]).map(val => val.id);
    const config = this.getCreateModalComponentOptions();
    const modalRef = this.modalService.show(SaleOrderShippingCreateComponent, config);
    let modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
    modal.setModel({order_ids: ids.join(',')});
    let sub = modal.onHidden.subscribe((result: ModalResult<SaleOrderShippingMeta>) => {
      if (result.success) {
        const config2 = {'class': 'modal-huge'};
        const modalRef2 = this.modalService.show(SaleOrderShippingCreateResultComponent, config2);
        let modal2: AbstractCRUDModalComponent<any> = <AbstractCRUDModalComponent<any>>modalRef2.content;
        modal2.setRelatedModel(result.data);
        let sub2 = modal2.onHidden.subscribe((result: ModalResult<SaleOrderShippingMeta>) => {
          sub2.unsubscribe();
        });
      }
      this.load();
      sub.unsubscribe();
    });
  }

  selectAll() {
    this.statusSelectAll = !this.statusSelectAll;
    this.selectors = this.selectors.map(val => this.statusSelectAll);
  }

  selectItem(event: SaleOrderMeta, index: number) {
    this.selectors[index] = !this.selectors[index];
    let status = true;
    this.selectors.forEach(val => {
      if (!val) {
        status = false;
      }
    });
    this.statusSelectAll = status;
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

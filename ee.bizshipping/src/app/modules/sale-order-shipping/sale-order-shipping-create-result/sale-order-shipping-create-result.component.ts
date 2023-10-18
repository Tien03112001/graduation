import {Component} from '@angular/core';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {SaleOrderMeta} from '../../sale-order/sale-order.meta';
import {SaleOrderService} from '../../sale-order/sale-order.service';
import {ExportingNoteService} from '../../exporting-note/exporting-note.service';
import {AbstractCRUDModalComponent} from '../../../core';

@Component({
  selector: 'app-order-shipping-create-result',
  templateUrl: './sale-order-shipping-create-result.component.html',
  styleUrls: ['./sale-order-shipping-create-result.component.css'],
  providers: [SaleOrderService, ExportingNoteService]
})

export class SaleOrderShippingCreateResultComponent extends AbstractCRUDModalComponent<any> {

  statusSelectAll: boolean = false;
  selectors: boolean[];
  bill: any;

  buildSearchForm(): FormGroup {
    return undefined;
  }

  getCreateModalComponent(): any {
  }

  getCreateModalComponentOptions(): ModalOptions {
    return undefined;
  }

  getEditModalComponent(): any {
  }

  getEditModalComponentOptions(): ModalOptions {
    return undefined;
  }

  getTitle():any {
  };

  initNewModel(): any {
    return undefined;
  }


  onInit(): void {
  }

  onDestroy(): void {
  }

  loaded(): void {
    this.list = this.relatedModel['orders'];
    this.selectors = this.list.map(val => false);
    this.bill = this.relatedModel['exporting_note'];
  }

  constructor(
    service: SaleOrderService,
    modal: BsModalRef,
    modalService: BsModalService,
    builder: FormBuilder,
    private exportingNoteService: ExportingNoteService
  ) {
    super(service, modal, modalService, builder);
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

  printShippingBill() {
    let ids: number[] = this.list.filter((val, index) => this.selectors[index]).map(val => val.id);
    (<SaleOrderService>this.service).printShippingBill(ids).subscribe(res => {
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

  printExportingBill() {
    this.exportingNoteService.printBill(this.bill['id']).subscribe(res => {
      this.service.toastSuccessfully('In phiếu kho');
      if (res['link']) {
        window.open(res['link']);
      }
      if (res['src']) {
        var win = window.open('', 'In phiếu kho', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=200,top=' + (screen.height - 400) + ',left=' + (screen.width - 840));
        win.document.body.innerHTML = res['src'];
      }
      this.load();
    }, () => this.service.toastFailed('In phiếu kho'));
  }
}

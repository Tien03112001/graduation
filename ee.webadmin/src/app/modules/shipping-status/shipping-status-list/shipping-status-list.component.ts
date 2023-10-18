import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ShippingStatusMeta} from '../shipping-status.meta';
import {ShippingStatusService} from '../shipping-status.service';
import {ShippingStatusCreateComponent} from '../shipping-status-create/shipping-status-create.component';
import {ShippingStatusEditComponent} from '../shipping-status-edit/shipping-status-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {ModalResult} from '../../../core/common';
import {ShippingStatusEditVnpayComponent} from '../shipping-status-vnpay-edit/shipping-status-vnpay-edit.component';
import {PaymentMeta} from '../../payment/payment.meta';

@Component({
  selector: 'app-shipping-status',
  templateUrl: './shipping-status-list.component.html',
  styleUrls: ['./shipping-status-list.component.css'],
  providers: [ShippingStatusService]
})
export class ShippingStatusListComponent extends AbstractCRUDComponent<ShippingStatusMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Trạng thái ship hàng';
  }

  getCreateModalComponent(): any {
    return ShippingStatusCreateComponent;
  }

  getEditModalComponent(): any {
    return ShippingStatusEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.maxLength(255)),
    });
  }

  initNewModel(): ShippingStatusMeta {
    return new ShippingStatusMeta();
  }

  constructor(
    service: ShippingStatusService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  public create() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<ShippingStatusMeta> = <AbstractModalComponent<ShippingStatusMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ShippingStatusMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  addManual(item: ShippingStatusMeta) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(ShippingStatusEditComponent, config);
    const modal: AbstractModalComponent<ShippingStatusMeta> = <AbstractModalComponent<ShippingStatusMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(item));
    const sub = modal.onHidden.subscribe((result: ModalResult<ShippingStatusMeta>) => {
      if (result.success) {
            this.load();
          }
      sub.unsubscribe();
    });
  }

  addVnpay(item: ShippingStatusMeta) {
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, this.getEditModalComponentOptions());
    const modalRef = this.modalService.show(ShippingStatusEditVnpayComponent, config);
    const modal: AbstractModalComponent<ShippingStatusMeta> = <AbstractModalComponent<ShippingStatusMeta>>modalRef.content;
    modal.setModel(ObjectUtil.clone(item));
    const sub = modal.onHidden.subscribe((result: ModalResult<ShippingStatusMeta>) => {
      if (result.success) {
            this.load();
          }
      sub.unsubscribe();
    });
  }
  destroy(item: ShippingStatusMeta, i: number) {
    (<ShippingStatusService>this.service).destroy(item.id).subscribe(res => {
      this.service.toastSuccessfully('Xóa');
      this.load();
    }, () => this.service.toastFailedEdited());
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

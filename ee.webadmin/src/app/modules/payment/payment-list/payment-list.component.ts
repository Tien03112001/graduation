import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {PaymentMeta} from '../payment.meta';
import {PaymentService} from '../payment.service';
import {PaymentCreateComponent} from '../payment-create/payment-create.component';
import {PaymentEditComponent} from '../payment-edit/payment-edit.component';
import { AbstractCRUDComponent, AbstractModalComponent } from '../../../core/crud';
import { FieldForm, ModalResult } from '../../../core/common';
import { TitleService } from '../../../core/services';
import { ObjectUtil } from '../../../core/utils';

@Component({
  selector: 'app-payment-list',
  templateUrl: './payment-list.component.html',
  styleUrls: ['./payment-list.component.css'],
  providers: [PaymentService]
})
export class PaymentListComponent extends AbstractCRUDComponent<PaymentMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Danh sách hình thức thanh toán';
  }

  getCreateModalComponent(): any {
    return PaymentCreateComponent;
  }

  getEditModalComponent(): any {
    return PaymentEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null, Validators.maxLength(255)),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo hình thức thanh toán', 'search', 'Nhập từ khóa')
    ];
  }

  initNewModel(): PaymentMeta {
    return new PaymentMeta();
  }

  constructor(
    service: PaymentService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
  }

  public createPayment() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<PaymentMeta> = <AbstractModalComponent<PaymentMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<PaymentMeta>) => {
      if (result.success) {
        this.load();
      }
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

import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {PaymentmtMeta} from '../paymentmt.meta';
import {PaymentmtService} from '../paymentmt.service';
import {PaymentmtCreateComponent} from '../paymentmt-create/paymentmt-create.component';
import {PaymentmtEditComponent} from '../paymentmt-edit/paymentmt-edit.component';
import { AbstractCRUDComponent, AbstractModalComponent } from '../../../core/crud';
import { FieldForm, ModalResult } from '../../../core/common';
import { TitleService } from '../../../core/services';
import { ObjectUtil } from '../../../core/utils';

@Component({
  selector: 'app-paymentmt-list',
  templateUrl: './paymentmt-list.component.html',
  styleUrls: ['./paymentmt-list.component.css'],
  providers: [PaymentmtService]
})
export class PaymentmtListComponent extends AbstractCRUDComponent<PaymentmtMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Cấu hình phương thức thanh toán';
  }

  getCreateModalComponent(): any {
    return PaymentmtCreateComponent;
  }

  getEditModalComponent(): any {
    return PaymentmtEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm theo tên phương thức thanh toán', 'search', 'Nhập từ khóa')
    ];
  }

  initNewModel(): PaymentmtMeta {
    return new PaymentmtMeta();
  }

  constructor(
    service: PaymentmtService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
  }

  public createPaymentmt() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<PaymentmtMeta> = <AbstractModalComponent<PaymentmtMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<PaymentmtMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  public goToPageNumber() {
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (this.nextPage > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }
}

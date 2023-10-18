import {Component} from '@angular/core';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {ShippingUnitsMeta} from '../shipping_units.meta';
import {ShippingUnitsService} from '../shipping_units.service';
import {ShippingUnitsCreateComponent} from '../shipping_units-create/shipping_units-create.component';
import {ShippingUnitsEditComponent} from '../shipping_units-edit/shipping_units-edit.component';
import { AbstractCRUDComponent, AbstractModalComponent } from '../../../core/crud';
import { FieldForm, ModalResult } from '../../../core/common';
import { TitleService } from '../../../core/services';
import { ObjectUtil } from '../../../core/utils';
import {PaymentMethodMeta} from '../../payment-method/payment-method.meta';
import {PaymentMethodService} from '../../payment-method/payment-method.service';
import {ShippingStoreMeta} from '../../shipping-store/shipping-store.meta';
import {ShippingServiceMeta} from '../../shipping-service/shipping-service.meta';
import {Shipping_unitsPartnerComponent} from '../shipping_units-partner/shipping_units-partner.component';

@Component({
  selector: 'app-shipping_units-list',
  templateUrl: './shipping_units-list.component.html',
  styleUrls: ['./shipping_units-list.component.css'],
  providers: [ShippingUnitsService]
})
export class ShippingUnitsListComponent extends AbstractCRUDComponent<ShippingUnitsMeta> {
  stores = new ShippingStoreMeta();
  services = new ShippingServiceMeta();
  onInit(): void {
    this.load();
  }



  onDestroy(): void {
  }

  getTitle(): string {
    return 'Danh sách đối tác vận chuyển';
  }

  getCreateModalComponent(): any {
    return ShippingUnitsCreateComponent;
  }

  getEditModalComponent(): any {
    return ShippingUnitsEditComponent;
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
      FieldForm.createTextInput('Tìm kiếm theo tên đối tác vận chuyển', 'search', 'Nhập từ khóa')
    ];
  }

  initNewModel(): ShippingUnitsMeta {
    return new ShippingUnitsMeta();
  }

  constructor(
    service: ShippingUnitsService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder,);
  }

  public createShippingUnits() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<ShippingUnitsMeta> = <AbstractModalComponent<ShippingUnitsMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ShippingUnitsMeta>) => {
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
  sync(item: ShippingUnitsMeta, i: number) {
    (<ShippingUnitsService>this.service).sync(item.id).subscribe(res => {
      this.service.toastSuccessfully('Đồng bộ');
      this.load();
    }, () => this.service.toastFailedEdited());
  }
  addUnitPartner() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(Shipping_unitsPartnerComponent, config);
    let modal: AbstractModalComponent<ShippingUnitsMeta> = <AbstractModalComponent<ShippingUnitsMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ShippingUnitsMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }
}

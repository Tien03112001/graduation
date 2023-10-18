import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {SaleOrderShippingService} from '../sale-order-shipping.service';
import {ShippingServiceMeta} from '../../shipping-service/shipping-service.meta';
import {DefinitionService} from '../../definition/definition.service';
import {ShippingStoreMeta} from '../../shipping-store/shipping-store.meta';
import {ShippingUnitMeta} from '../../shipping-unit/shipping-unit.meta';
import { AbstractModalComponent, FieldForm } from '../../../core';

@Component({
  selector: 'app-order-shipping-create',
  templateUrl: './sale-order-shipping-create.component.html',
  styleUrls: ['./sale-order-shipping-create.component.css'],
  providers: [SaleOrderShippingService, DefinitionService]
})
export class SaleOrderShippingCreateComponent extends AbstractModalComponent<any> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      order_ids: new FormControl(null, Validators.required),
      deliver_id: new FormControl(null, [Validators.required]),
      store_id: new FormControl(null, [Validators.required]),
      service_id: new FormControl(null, [Validators.required])
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSelect('Chọn bên giao hàng', 'deliver_id', 'Chọn một', [], ['id', 'name']),
      FieldForm.createSelect('Chọn Kho giao', 'store_id', 'Chọn một', [], ['id', 'name']),
      FieldForm.createSelect('Chọn dịch vụ giao', 'service_id', 'Chọn một', [], ['id', 'name']),
    ];
  }

  loadAllShippingUnit() {
    this.defService.loadAllShippingUnit().subscribe(
      (val: ShippingUnitMeta[]) => {
        this.fields[0].data = val;
      }
    );
  }

  loadShippingStoreByUnit() {
    let unitId: number = +this.formGroup.get('deliver_id').value;
    this.defService.loadShippingStoreByUnit(unitId).subscribe(
      (val: ShippingStoreMeta[]) => {
        this.fields[1].data = val;
        this.formGroup.get('store_id').setValue(val[0].id);
      }
    );
  }

  loadShippingServiceByUnit() {
    let unitId: number = +this.formGroup.get('deliver_id').value;
    this.defService.loadShippingServiceByUnit(unitId).subscribe(
      (val: ShippingServiceMeta[]) => {
        this.fields[2].data = val;
        this.formGroup.get('service_id').setValue(val[0].id);
      }
    );
  }

  loaded(): void {
    this.loadAllShippingUnit();
  }

  constructor(
    service: SaleOrderShippingService,
    modal: BsModalRef,
    builder: FormBuilder,
    private defService: DefinitionService
  ) {
    super(service, modal, builder);
    this.formGroup.get('deliver_id').valueChanges.subscribe(value => {
      if (value) {
        this.loadShippingStoreByUnit();
        this.loadShippingServiceByUnit();
      }
    });
  }
}

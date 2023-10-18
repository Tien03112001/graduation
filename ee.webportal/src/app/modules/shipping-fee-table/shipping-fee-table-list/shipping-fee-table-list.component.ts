import {Component} from '@angular/core';
import {AbstractCRUDComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ShippingFeeTableMeta} from '../shipping-fee-table.meta';
import {ShippingFeeTableService} from '../shipping-fee-table.service';
import {ShippingFeeTableCreateComponent} from '../shipping-fee-table-create/shipping-fee-table-create.component';
import {ShippingFeeTableEditComponent} from '../shipping-fee-table-edit/shipping-fee-table-edit.component';
import {FieldForm} from '../../../core/common';
import {ProvinceService} from '../../province/province.service';
import {DistrictService} from '../../district/district.service';
import {WardService} from '../../ward/ward.service';

@Component({
  selector: 'app-shipping-fee-table',
  templateUrl: './shipping-fee-table-list.component.html',
  styleUrls: ['./shipping-fee-table-list.component.css'],
  providers: [ShippingFeeTableService, ProvinceService, DistrictService, WardService]
})
export class ShippingFeeTableListComponent extends AbstractCRUDComponent<ShippingFeeTableMeta> {
  onInit(): void {

    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'QUản lý phí ship';
  }

  getCreateModalComponent(): any {
    return ShippingFeeTableCreateComponent;
  }

  getEditModalComponent(): any {
    return ShippingFeeTableEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      province_id: new FormControl(null),
      province: new FormControl(null),
      district_id: new FormControl(null),
      district: new FormControl(null),
      ward_id: new FormControl(null),
      ward: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect2('Tìm kiếm theo tỉnh', 'province', 'Chọn một', [], {}, {enableSearchFilter: true}, 'col-md-6'),
      FieldForm.createSingleSelect2('Tìm kiếm theo huyện', 'district', 'Chọn một', [], {}, {enableSearchFilter: true}, 'col-md-6'),
      FieldForm.createSingleSelect2('Tìm kiếm theo xã', 'ward', 'Chọn một', [], {}, {enableSearchFilter: true}, 'col-md-6'),
    ];
  }


  loadProvinces(params: any) {
    this.provinceService.loadByParams(params).subscribe(value => {
      this.searchControls[0].data = value;
    });
  }

  loadDistricts(params: any) {
    this.districtService.loadByParams(params).subscribe(value => {
      this.searchControls[1].data = value;
    });
  }

  loadWards(params: any) {
    this.wardService.loadByParams(params).subscribe(value => {
      this.searchControls[2].data = value;
    });
  }

  initNewModel(): ShippingFeeTableMeta {
    return new ShippingFeeTableMeta();
  }

  constructor(
    service: ShippingFeeTableService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private provinceService: ProvinceService,
    private districtService: DistrictService,
    private wardService: WardService,
  ) {
    super(service, modal, builder);
    this.loadProvinces({});
    this.searchForm.controls['province'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.searchForm.controls['province_id'].setValue(value[0].id);
        this.searchForm.controls['district_id'].setValue(null);
        this.searchForm.controls['ward_id'].setValue(null);
        this.searchForm.controls['district'].setValue(null);
        this.searchForm.controls['ward'].setValue(null);
        this.loadDistricts({province_id: value[0].id});
        this.loadWards({province_id: value[0].id});
      }
    });

    this.searchForm.controls['district'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.searchForm.controls['district_id'].setValue(value[0].id);
        this.searchForm.controls['ward_id'].setValue(null);
        this.searchForm.controls['ward'].setValue(null);
        this.loadWards({province_id: this.searchForm.controls['province_id'].value,district_id: value[0].id});
      }
    });

    this.searchForm.controls['ward'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.searchForm.controls['ward_id'].setValue(value[0].id);
      }
    });
  }


}

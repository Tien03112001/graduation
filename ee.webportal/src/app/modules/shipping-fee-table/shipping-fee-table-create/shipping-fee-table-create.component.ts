import {Component} from '@angular/core';
import {ShippingFeeTableMeta} from '../shipping-fee-table.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ShippingFeeTableService} from '../shipping-fee-table.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import {ProvinceService} from '../../province/province.service';
import {DistrictService} from '../../district/district.service';
import {WardService} from '../../ward/ward.service';

@Component({
  selector: 'app-shipping-fee-table-create',
  templateUrl: './shipping-fee-table-create.component.html',
  styleUrls: ['./shipping-fee-table-create.component.css'],
  providers: [ShippingFeeTableService, ProvinceService, DistrictService, WardService]
})
export class ShippingFeeTableCreateComponent extends AbstractModalComponent<ShippingFeeTableMeta> {
  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }


  buildForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      province_id: new FormControl(null),
      province: new FormControl(null, Validators.required),
      district_id: new FormControl(null,),
      district: new FormControl(null, Validators.required),
      ward_id: new FormControl(null,),
      ward: new FormControl(null, Validators.required),
      fee: new FormControl(null, Validators.required),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createMultiSelect2('Chọn tỉnh', 'province', 'Chọn nhiều', 'loadProvinces'),
      FieldForm.createMultiSelect2('Chọn huyện', 'district', 'Chọn nhiều', 'loadDistricts'),
      FieldForm.createMultiSelect2('Chọn xã', 'ward', 'Chọn nhiều', 'loadWards'),
      FieldForm.createNumberInput('Tiền ship *', 'fee', 'Nhập Tiền ship'),
    ];
  }

  loaded(): void {
  }

  loadProvinces(params: any) {
    return this.provinceService.loadByParams(params).subscribe(value => {
      this.fields[0].data = value;
    });
  }


  loadDistricts(params: any) {
    return this.districtService.loadByParams(params).subscribe(value => {
      this.fields[1].data = value;
    });
  }

  loadWards(params: any) {
    return this.wardService.loadByParams(params).subscribe(value => {
      this.fields[2].data = value;
    });
  }

  constructor(
    service: ShippingFeeTableService,
    modal: BsModalRef,
    builder: FormBuilder,
    private provinceService: ProvinceService,
    private districtService: DistrictService,
    private wardService: WardService,
  ) {
    super(service, modal, builder);
    this.loadProvinces({});
    this.formGroup.controls['province'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['province_id'].setValue(value[0].id);
        this.formGroup.controls['district_id'].setValue(null);
        this.formGroup.controls['ward_id'].setValue(null);
        this.formGroup.controls['district'].setValue(null);
        this.formGroup.controls['ward'].setValue(null);
        this.loadDistricts({province_id: value[0].id});
        this.loadWards({province_id: value[0].id});
      }
    });

    this.formGroup.controls['district'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['district_id'].setValue(value[0].id);
        this.formGroup.controls['ward_id'].setValue(null);
        this.formGroup.controls['ward'].setValue(null);
        this.loadWards({province_id: this.formGroup.controls['province_id'].value, district_id: value[0].id});
      }
    });

    this.formGroup.controls['ward'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['ward_id'].setValue(value[0].id);
      }
    });
  }
}

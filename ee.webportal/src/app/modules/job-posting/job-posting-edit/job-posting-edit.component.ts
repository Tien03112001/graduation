import {Component} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {JobPostingMeta} from '../job-posting.meta';
import {JobPostingService} from '../job-posting.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import {ProvinceService} from '../../province/province.service';
import {ObjectUtil} from '../../../core/utils';

@Component({
  selector: 'app-job-posting-edit',
  templateUrl: './job-posting-edit.component.html',
  styleUrls: ['./job-posting-edit.component.css'],
  providers: [JobPostingService, ProvinceService]
})
export class JobPostingEditComponent extends AbstractModalComponent<JobPostingMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllProvince() {
    return this.provinceService.loadAll().finally(() => {
      let selector = this.fields[3].data.filter(val => val.name == this.model.job_location);
      this.formGroup.controls['job_location_selector'].setValue(selector);
    });
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      title: new FormControl(null, Validators.required),
      description: new FormControl(null),
      image: new FormControl(null, Validators.required),
      job_location: new FormControl(null, Validators.required),
      job_location_selector: new FormControl(null),
      base_salary_min: new FormControl(null),
      base_salary_max: new FormControl(null),
      unit_currency: new FormControl(null, Validators.required),
      unit_time: new FormControl(null, Validators.required),
      date_posted: new FormControl(null, Validators.required),
      valid_through: new FormControl(null, Validators.required),
      quantity: new FormControl(null, Validators.required),
      type: new FormControl(''),
      published: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tiêu đề *', 'title', 'Nhập tiêu đề'),
      FieldForm.createHtmlInput('Mô tả *', 'description', 'Nhập mô tả'),
      FieldForm.createFileInput('Ảnh', 'image', 'Chọn ảnh', this.onImageUploadChange, 'image/*'),
      FieldForm.createSingleSelect2('Địa điểm *', 'job_location_selector', 'Nhập địa điểm', 'loadAllProvince'),
      FieldForm.createNumberInput('Lương tối thiểu', 'base_salary_min', 'Nhập lương tối thiểu'),
      FieldForm.createNumberInput('Lương tối đa', 'base_salary_max', 'Nhập lương tối đa'),
      FieldForm.createSelect('Đơn vị tiền tệ', 'unit_currency', 'Nhập đơn vị tiền tệ', [
        {
          value: 'VND',
          name: 'VND',
        },
        {
          value: 'USD',
          name: 'USD'
        }
      ], null, 'name', 'value'),
      FieldForm.createSelect('Đơn vị thời gian', 'unit_time', 'Chọn một',
        [
          {
            value: 'hour',
            name: 'hour',
          },
          {
            value: 'day',
            name: 'day'
          },
          {
            value: 'month',
            name: 'month'
          },
          {
            value: 'year',
            name: 'year'
          }
        ], null, 'name', 'value'),
      FieldForm.createDateInput('Ngày đăng', 'date_posted', 'Chọn ngày đăng'),
      FieldForm.createDateInput('Ngày thông qua', 'valid_through', 'Chọn ngày thông qua'),
      FieldForm.createNumberInput('Số lượng', 'quantity', 'Nhập số lượng'),
      FieldForm.createSelect('Loại tin', 'type', 'Chọn một', [
        {
          value: 'HOT',
          name: 'HOT',
        },
        {
          value: 'ưu tiên',
          name: 'Ưu tiên',
        },
        {
          value: 'Gấp',
          name: 'Gấp',
        },
      ], null, 'name', 'value'),
    ];
  }

  loaded(): void {

  }

  prepareParams() {
    let data = ObjectUtil.combineValue(this.model, this.formGroup.value);
    if (this.formGroup.controls['published'].value) {
      data['published'] = 1;
    } else {
      data['published'] = 0;
    }
    if (this.formGroup.controls['type'].value == null) {
      data['type'] = null;
    }
    return data;
  }

  constructor(
    service: JobPostingService,
    modal: BsModalRef,
    builder: FormBuilder,
    private provinceService: ProvinceService
  ) {
    super(service, modal, builder);
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }

  onFormChanged() {
    this.formGroup.controls['job_location_selector'].valueChanges.subscribe(value => {
        if (value && value.length > 0) {
          this.formGroup.controls['job_location'].setValue(value[0].name);
        } else {
          this.formGroup.controls['job_location'].setValue(null);
        }
      }
    );
  }

}

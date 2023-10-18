import {Component} from '@angular/core';
import {JobPostingMeta} from '../job-posting.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {JobPostingService} from '../job-posting.service';
import {AbstractModalComponent} from '../../../core/crud';
import {FieldForm} from '../../../core/common';
import * as moment from 'moment';
import {ProvinceService} from '../../province/province.service';

@Component({
  selector: 'app-job-posting-create',
  templateUrl: './job-posting-create.component.html',
  styleUrls: ['./job-posting-create.component.css'],
  providers: [JobPostingService, ProvinceService]
})
export class JobPostingCreateComponent extends AbstractModalComponent<JobPostingMeta> {


  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      title: new FormControl(null, [Validators.required, Validators.maxLength(255)]),
      description: new FormControl(null, Validators.required),
      image: new FormControl(null, Validators.required),
      job_location: new FormControl(null, Validators.required),
      job_location_selector: new FormControl(null, Validators.required),
      base_salary_min: new FormControl(null),
      base_salary_max: new FormControl(null),
      unit_currency: new FormControl('VND', Validators.required),
      unit_time: new FormControl('month', Validators.required),
      date_posted: new FormControl(moment().format('YYYY-MM-DD'), Validators.required),
      valid_through: new FormControl(moment().add('days', 30).format('YYYY-MM-DD'), Validators.required),
      quantity: new FormControl(1, Validators.required),
      type: new FormControl(null),
      published: new FormControl(true),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tiêu đề *', 'title', 'Nhập tiêu đề'),
      FieldForm.createCheckbox('Trạng thái', 'published', 'Chọn trạng thái'),
      FieldForm.createHtmlInput('Mô tả *', 'description', 'Nhập mô tả'),
      FieldForm.createFileInput('Ảnh', 'image', 'Chọn ảnh', this.onFileUploadChange, 'image/*'),
      FieldForm.createSingleSelect2('Địa điểm *', 'job_location_selector', 'Nhập địa điểm', 'loadAllProvince'),
      FieldForm.createNumberInput('Lương tối thiểu', 'base_salary_min', 'Nhập lương tối thiểu'),
      FieldForm.createNumberInput('Lương tối đa', 'base_salary_max', 'Nhập lương tối đa'),
      FieldForm.createSelect('Đơn vị tiền tệ', 'unit_currency', 'Chọn một', [
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
      ], null,'name', 'value'),
    ];
  }

  loaded(): void {
  }

  createWithImage() {
    if (this.formGroup.controls['published'].value) {
      this.formGroup.controls['published'].setValue(1);
    } else {
      this.formGroup.controls['published'].setValue(0);
    }
    super.createWithImage();
  }

  loadAllProvince() {
    return this.provinceService.loadAll();
  }

  constructor(
    service: JobPostingService,
    modal: BsModalRef,
    builder: FormBuilder,
    private provinceService: ProvinceService,
  ) {
    super(service, modal, builder);
  }

  onFormChanged() {
    this.formGroup.controls['job_location_selector'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['job_location'].setValue(value[0].name);
      }
    })
  }
}

import {Component, ViewChild} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {Observable} from 'rxjs/Observable';
import {NgSelectComponent} from '@ng-select/ng-select';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {BannerGroupMeta} from '../banner-group.meta';
import {BannerGroupService} from '../banner-group.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-banner-edit',
  templateUrl: './banner-group-edit.component.html',
  styleUrls: ['./banner-group-edit.component.css'],
  providers: [BannerGroupService]
})
export class BannerGroupEditComponent extends AbstractModalComponent<BannerGroupMeta> {
  categories$: Observable<BannerGroupMeta[]>;

  @ViewChild('parentSelector') parentSelector: NgSelectComponent;

  setParent(c: BannerGroupMeta) {
    if (c) {
      this.parentSelector.select({value: c, label: c.name});
    }
  }

  onParentChange(e) {
    if (e) {
      this.formGroup.controls['parent_id'].setValue(e.id);
    } else {
      this.formGroup.controls['parent_id'].setValue(0);
    }
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên *', 'name', 'Nhập tên'),
    ];
  }

  loadAllCategories() {
    this.categories$ = this.service.loadAll();
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s][a-zA-Z0-9~`!@#$%^&*()+=?:;"\'_ÂÀÁÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ ]*$')]),
    });
  }

  loaded(): void {
    this.formGroup.setValue({
      name: this.model.name,
      code: this.model.code,
    });
    this.setParent(this.model.parent);
  }

  constructor(
    service: BannerGroupService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
    this.loadAllCategories();
  }


}

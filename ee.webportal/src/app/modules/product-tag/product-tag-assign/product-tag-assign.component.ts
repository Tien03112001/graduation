import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ProductTagService} from '../product-tag.service';
import {ObjectUtil} from '../../../core/utils';
import {ProductMeta} from '../../product/product.meta';
import {ProductService} from '../../product/product.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-product-tag-assign',
  templateUrl: './product-tag-assign.component.html',
  styleUrls: ['./product-tag-assign.component.css'],
  providers: [ProductService, ProductTagService]
})
export class ProductTagAssignComponent extends AbstractModalComponent<ProductMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllTags(params:any) {
    return this.tagService.loadByParams(params);
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      ids: new FormControl(null, Validators.required),
      tags: new FormControl(null, Validators.required)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createMultiSelect2('Thẻ sản phẩm', 'tags', 'Chọn nhiều', 'loadAllTags')
    ];
  }

  loaded(): void {
    this.tagService.loadByParams({product_id: this.model.id}).subscribe(value => {
      this.formGroup.controls['tags'].setValue(value);
    });
  }

  assign() {
    let ids: number[] = this.formGroup.controls['ids'].value;
    (<ProductService>this.service).assignTags(this.model.id, ids).subscribe((res: ProductMeta) => {
      this.service.toastSuccessfullyEdited();
      this.close(ObjectUtil.mergeValue(this.model, res));
    }, () => this.service.toastFailedEdited());
  }

  constructor(
    service: ProductService,
    modal: BsModalRef,
    builder: FormBuilder,
    private tagService: ProductTagService
  ) {
    super(service, modal, builder);
  }

  onFormChanged(): void {
    super.onFormChanged();
    this.formGroup.controls['tags'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        this.formGroup.controls['ids'].setValue(value.map(v => v.id));
      } else {
        this.formGroup.controls['ids'].setValue(null);
      }
    });
  }

}

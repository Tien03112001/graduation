import { Component, Input, ViewChild } from '@angular/core';
import { AbstractModalComponent } from '../../../core/crud';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { RelatedProductMeta } from '../related-product.meta';
import { RelatedProductService } from '../related-product.service';
import { FieldForm } from '../../../core/common';
import { ProductService } from '../../product/product.service';
import { ObjectUtil } from '../../../core/utils';

@Component({
  selector: 'app-related-product-create',
  templateUrl: './related-product-create.component.html',
  styleUrls: ['./related-product-create.component.css'],
  providers: [RelatedProductService, ProductService]
})
export class RelatedProductCreateComponent extends AbstractModalComponent<RelatedProductMeta> {
  ckEditorConfig: any = {
    height: '350px'
  };

  public products = [];

  product_id: any;

  ObjectUtil: any;

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      related_id: new FormControl(),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('', 'related_id', ''),
    ];
  }

  loaded(): void {
    this.load();
  }

  formSearchProduct: FormGroup = new FormGroup({
    search: new FormControl(),
  });

  constructor(
    service: RelatedProductService,
    modal: BsModalRef,
    builder: FormBuilder,
    private productService: ProductService,
  ) {
    super(service, modal, builder);
  }

  public load() {
    let relatedProducts = this.model.existsRelations;
    this.productService.loadAll().subscribe((products) => {
      this.products = products.filter(value => {
        let relatedExists = relatedProducts.filter(v => v.related_id == value.id);
        if (relatedExists.length == 0) {
          return value.id != this.model.product_id;
        }
        return false;
      });
    });
  }

  createProduct(p) {
    let item = ObjectUtil.ignoreNullValue(this.model);
    const data = { product_id: item.product_id, related_id: p.id };
    this.service.storeRelated(data).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
  }

  searchProduct() {
    let relatedProducts = this.model.existsRelations;
    let params: any = ObjectUtil.combineValue({}, this.formSearchProduct.value, true);
    this.productService.loadByParams(params).subscribe((products) => {
      this.products = products.filter(value => {
        let relatedExists = relatedProducts.filter(v => v.related_id == value.id);
        if (relatedExists.length == 0) {
          return value.id != this.model.product_id;
        }
        return false;
      });
    });
  }

}

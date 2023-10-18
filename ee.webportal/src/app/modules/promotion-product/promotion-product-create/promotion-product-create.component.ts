import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {PromotionProductMeta} from '../promotion-product.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {PromotionProductService} from '../promotion-product.service';
import {FieldForm} from '../../../core/common';
import {ProductService} from '../../product/product.service';
import {ProductTagService} from '../../product-tag/product-tag.service';
import {ProductMeta} from '../../product/product.meta';
import {ObjectUtil} from '../../../core/utils';
import {PromotionService} from '../../promotion/promotion.service';
import {PromotionMeta} from '../../promotion/promotion.meta';
import {RelatedProductMeta} from '../../related-product/related-product.meta';

@Component({
  selector: 'app-promotion-product-create',
  templateUrl: './promotion-product-create.component.html',
  styleUrls: ['./promotion-product-create.component.css'],
  providers: [PromotionProductService, ProductService]
})
export class PromotionProductCreateComponent extends AbstractModalComponent<PromotionProductMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };
  listAvailableProducts: any;

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      ids: new FormControl(null, Validators.required),
      products: new FormControl(null, Validators.required),
    });
  }

  formSearchProduct: FormGroup = new FormGroup({
    search: new FormControl(),
  });

  public load() {
      let product_id =  this.model.product_id;
    this.productService.loadAvailableProducts(product_id,null ).subscribe((res: any) => {
        this.listAvailableProducts = res;
      }, () => {
        this.listAvailableProducts = [];
      }
    );
  }
  loaded(): void {
    this.load();
  }
  assign(item: ProductMeta) {
    let ids: number = item.id;
    (<PromotionProductService>this.service).assignProducts(this.model.product_id, ids).subscribe((res: ProductMeta) => {
      this.service.toastSuccessfullyEdited();
      this.load();
    }, () => this.service.toastFailedEdited());
  }
  searchProduct() {
     let search = this.formSearchProduct.value;
      let product_id =  this.model.product_id;
    this.productService.loadAvailableProducts(product_id,search).subscribe((res: any) => {
        this.listAvailableProducts = res;
      }, () => {
        this.listAvailableProducts = [];
      }
    );
  }
  removeFilter() {
    this.formSearchProduct.reset();
  }

  constructor(
    service: PromotionProductService,
    modalRef: BsModalRef,
    builder: FormBuilder,
    private productService: ProductService
  ) {
    super(service, modalRef, builder);
  }
  getCreateModalComponent(): any {
  }

  getCreateModalComponentOptions(): ModalOptions {
    return undefined;
  }

  getEditModalComponent(): any {
  }

  getEditModalComponentOptions(): ModalOptions {
    return undefined;
  }

  getTitle(): string {
    return '';
  }

  initNewModel(): PromotionProductMeta {
    return undefined;
  }
  dismiss() {
    super.dismiss();
  }


}

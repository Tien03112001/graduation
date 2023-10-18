import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap';
import { AbstractCRUDModalComponent, AbstractModalComponent } from '../../../core/crud';
import { TitleService } from '../../../core/services';

import {PromotionProductCreateComponent} from '../promotion-product-create/promotion-product-create.component';
import {PromotionProductEditComponent} from '../promotion-product-edit/promotion-product-edit.component';
import {PromotionProductService} from '../promotion-product.service';
import {PromotionProductMeta} from '../promotion-product.meta';
import {ObjectUtil} from '../../../core/utils';
import {ModalResult} from '../../../core/common';
import {ProductMeta} from '../../product/product.meta';
import {PromotionMeta} from '../../promotion/promotion.meta';

@Component({
  selector: 'app-promotion-product-list',
  templateUrl: './promotion-product-list.component.html',
  styleUrls: ['./promotion-product-list.component.css'],
  providers: [PromotionProductService]
})
export class PromotionProductListComponent extends AbstractCRUDModalComponent<PromotionProductMeta> {
  formBuilder: any;

  getTitle(): string {
    return 'Quản lý sản phẩm khuyến mãi';
  }

  getCreateModalComponent(): any {
    return PromotionProductCreateComponent;
  }

  getEditModalComponent(): any {
    return PromotionProductEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return { 'class': 'modal-lg' };
  }

  getEditModalComponentOptions(): ModalOptions {
    return { 'class': 'modal-lg' };
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      User_id: new FormControl(null, Validators.required)
    });
  }

  initNewModel(): PromotionProductMeta {
    let product: PromotionProductMeta = new PromotionProductMeta();
    product.product_id = this.relatedModel.id;
    product.existsProducts = this.list;
    return product;
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  loaded(): void {
    this.load();
  }

  constructor(
    service: PromotionProductService,
    modal: BsModalRef,
    modalService: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, modalService, builder);
  }

  public load() {
    let param = {
      promotion_id: this.relatedModel.id,
    };
    this.service.loadByParams(param).subscribe((res: PromotionProductMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }
  createPromotionProduct() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<PromotionProductMeta> = <AbstractModalComponent<PromotionProductMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<PromotionProductMeta>) => {
      this.load();
    });
  }

  removeProduct(product_id: number,index :number) {
    (<PromotionProductService>this.service).detachProduct(this.relatedModel.id,{
      product_id: product_id,
      promotion_id: this.relatedModel.id,
    })
      .subscribe(res => {
      this.list.splice(index, 1);
      this.service.toastSuccessfullyDeleted();
      this.load();
    }, () => this.service.toastFailedDeleted());
  }
}

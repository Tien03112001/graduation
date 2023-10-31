import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef, BsModalService, ModalOptions } from 'ngx-bootstrap';
import { AbstractCRUDModalComponent, AbstractModalComponent } from '../../../core/crud';
import { TitleService } from '../../../core/services';

import {OrderProductCreateComponent} from '../order-product-create/order-product-create.component';
import {OrderProductEditComponent} from '../order-product-edit/order-product-edit.component';
import {OrderProductService} from '../order-product.service';
import {OrderProductMeta} from '../order-product.meta';
import {ObjectUtil} from '../../../core/utils';
import {FieldForm, ModalResult} from '../../../core/common';
import {ProductMeta} from '../../product/product.meta';
import {PromotionMeta} from '../../promotion/promotion.meta';

@Component({
  selector: 'app-order-product-list',
  templateUrl: './order-product-list.component.html',
  styleUrls: ['./order-product-list.component.css'],
  providers: [OrderProductService]
})
export class OrderProductListComponent extends AbstractCRUDModalComponent<OrderProductMeta> {
  formBuilder: any;

  getTitle(): string {
    return 'Quản lý sản phẩm khuyến mãi';
  }

  getCreateModalComponent(): any {
    return OrderProductCreateComponent;
  }

  getEditModalComponent(): any {
    return OrderProductEditComponent;
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
  initNewModel(): OrderProductMeta {
    let product: OrderProductMeta = new OrderProductMeta();
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
    service: OrderProductService,
    modal: BsModalRef,
    modalService: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, modalService, builder);
  }

  public load() {
    let param = {
      order_id: this.relatedModel.id,
    };
    this.service.loadByParams(param).subscribe((res: OrderProductMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }
  createOrderProduct() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<OrderProductMeta> = <AbstractModalComponent<OrderProductMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<OrderProductMeta>) => {
      this.load();
    });
  }

  // removeProduct(product_id: number,index :number) {
  //   (<OrderProductService>this.service).detachProduct(this.relatedModel.id,{
  //     product_id: product_id,
  //     promotion_id: this.relatedModel.id,
  //   })
  //     .subscribe(res => {
  //     this.list.splice(index, 1);
  //     this.service.toastSuccessfullyDeleted();
  //     this.load();
  //   }, () => this.service.toastFailedDeleted());
  // }
}

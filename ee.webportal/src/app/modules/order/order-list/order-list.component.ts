import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {OrderMeta} from '../order.meta';
import {OrderService} from '../order.service';
import {OrderCreateComponent} from '../order-create/order-create.component';
import {OrderEditComponent} from '../order-edit/order-edit.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {ArticleCommentMeta} from '../../article-comment/article-comment.meta';
import {PromotionMeta} from '../../promotion/promotion.meta';
import {ObjectUtil} from '../../../core/utils';
import {
  PromotionProductListComponent
} from '../../promotion-product/promotion-product-list/promotion-product-list.component';
import {PromotionProductMeta} from '../../promotion-product/promotion-product.meta';
import {OrderProductListComponent} from '../../order-product/order-product-list/order-product-list.component';
import {OrderProductMeta} from '../../order-product/order-product.meta';

@Component({
  selector: 'app-order-list',
  templateUrl: './order-list.component.html',
  styleUrls: ['./order-list.component.css'],
  providers: [OrderService]
})
export class OrderListComponent extends AbstractCRUDComponent<OrderMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý order';
  }

  getCreateModalComponent(): any {
    return OrderCreateComponent;
  }

  getEditModalComponent(): any {
    return OrderEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-huge'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-huge'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      published: new FormControl(''),
    });
  }

  public initSearchForm(): FieldForm[] {
    return [
      {
        label: 'Tìm kiếm theo tên trang',
        type: 'input',
        typeof: 'text',
        formControl: 'search',
        placeHolder: 'Tìm kiếm theo tên trang',
      },
      {
        label: 'Tìm kiếm theo trạng thái',
        type: 'select',
        typeof: 'text',
        formControl: 'published',
        placeHolder: 'Từ khóa',
        data: [
          {
            id: '',
            name: 'Tất cả'
          },
          {
            id: 1,
            name: 'Hoạt động'
          },
          {
            id: 0,
            name: 'Không hoạt động'
          },
        ]
      },
    ];
  }

  initNewModel(): OrderMeta {
    return new OrderMeta();
  }

  constructor(
    service: OrderService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }
  // editOrder(item: OrderMeta, i: number) {
  //   let modalOptions = Object.assign(this.defaultModalOptions(), {'class': 'modal-huge'});
  //   const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
  //   const modalRef = this.modalService.show(OrderProductListComponent, config);
  //   let modal: AbstractCRUDModalComponent<OrderProductMeta> = <AbstractCRUDModalComponent<OrderProductMeta>>modalRef.content;
  //   modal.setRelatedModel(item);
  //   let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
  //     if (result.success) {
  //       this.load();
  //     }
  //     sub.unsubscribe();
  //   });
  // }
  public editOrder(item: OrderMeta, index: number) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<OrderMeta> = <AbstractModalComponent<OrderMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<OrderMeta>) => {
      this.onEdited(result, item, index);
      sub.unsubscribe();
    });
  }
}

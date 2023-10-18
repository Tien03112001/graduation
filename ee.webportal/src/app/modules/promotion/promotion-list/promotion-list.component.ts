import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {PromotionMeta} from '../promotion.meta';
import {PromotionService} from '../promotion.service';
import {PromotionCreateComponent} from '../promotion-create/promotion-create.component';
import {PromotionEditComponent} from '../promotion-edit/promotion-edit.component';
import {FieldForm, ModalResult} from '../../../core/common';
import {ArticleCommentMeta} from '../../article-comment/article-comment.meta';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {ObjectUtil} from '../../../core/utils';
import {PromotionProductMeta} from '../../promotion-product/promotion-product.meta';
import {
  PromotionProductListComponent
} from '../../promotion-product/promotion-product-list/promotion-product-list.component';



@Component({
  selector: 'app-promotion-list',
  templateUrl: './promotion-list.component.html',
  styleUrls: ['./promotion-list.component.css'],
  providers: [PromotionService, MetaDataService]
})
export class PromotionListComponent extends AbstractCRUDComponent<PromotionMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý promotion';
  }

  getCreateModalComponent(): any {
    return PromotionCreateComponent;
  }

  getEditModalComponent(): any {
    return PromotionEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-huge'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
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

  initNewModel(): PromotionMeta {
    return new PromotionMeta();
  }

  constructor(
    service: PromotionService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private metaService: MetaDataService
  ) {
    super(service, modal, builder);
  }

  public goToPromotionNumber() {
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (this.nextPage > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }
  viewRole(item: PromotionMeta, i: number) {
    let modalOptions = Object.assign(this.defaultModalOptions(), {'class': 'modal-huge'});
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(PromotionProductListComponent, config);
    let modal: AbstractCRUDModalComponent<PromotionProductMeta> = <AbstractCRUDModalComponent<PromotionProductMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }
  // getProductList(item: PromotionMeta, index: number) {
  //   const modalRef = this.modalService.show(ProductPromotionAssignComponent, {
  //     'class': 'modal-lg',
  //     ignoreBackdropClick: true
  //   });
  //   let modal: AbstractModalComponent<PromotionMeta> = <AbstractModalComponent<PromotionMeta>>modalRef.content;
  //   modal.setModel(item);
  //   let sub = modal.onHidden.subscribe((result: ModalResult<PromotionMeta>) => {
  //     if (result.success) {
  //       let itemEdited: PromotionMeta = result.data;
  //       this.list[index].products = itemEdited.products;
  //     }
  //     sub.unsubscribe();
  //   });
  // }
  onPublishedChange(item: PromotionMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg = 'Hoạt động';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Không hoạt động';
    }
    methodAsync.subscribe((res: ArticleCommentMeta) => {
      this.list[index].enable = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }
}

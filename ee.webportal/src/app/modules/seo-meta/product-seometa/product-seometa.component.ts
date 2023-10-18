import {Component, ViewChild} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {AppPagination, ModalResult} from '../../../core/common';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';
import {Observable} from 'rxjs/Observable';
import {NgSelectComponent} from '@ng-select/ng-select';
import {ProductTagAssignComponent} from '../../product-tag/product-tag-assign/product-tag-assign.component';
import {StructuredDataMeta} from '../../structured-data/structured-data.meta';
import {StructuredDataListComponent} from '../../structured-data/structured-data-list/structured-data-list.component';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {ProductTagMeta} from '../../product-tag/product-tag.meta';
import {ProductTagService} from '../../product-tag/product-tag.service';
import {Product_categoryService} from '../../product_category/product_category.service';
import {Product_categoryMeta} from '../../product_category/product_category.meta';
import {ObjectUtil} from '../../../core/utils';
import {ProductService} from '../../product/product.service';
import {ProductMeta} from '../../product/product.meta';
import { RelatedProductListComponent } from '../../related-product/related-product-list/related-product-list.component';
import { RelatedProductMeta } from '../../related-product/related-product.meta';

@Component({
  selector: 'app-product',
  templateUrl: './product-seometa.component.html',
  styleUrls: ['./product-seometa.component.css'],
  providers: [ProductService, Product_categoryService, MetaDataService, ProductTagService]
})
export class ProductSeometaComponent extends AbstractCRUDComponent<ProductMeta> {
  slected: any;

  onInit(): void {
    this.loaded();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Tối ưu seo sản phẩm';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      published: new FormControl(''),
      category_id: new FormControl(null),
      tag_id: new FormControl(null),
    });
  }

  public loaded() {
    let params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
      orderBy : 'order:asc'
    }, this.searchForm.value, true);
    this.service.loadByPage(params).subscribe(res => {
        this.list = res.data;
        this.pagination.set(res);
      }, () => {
        this.list = [];
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
      }
    );
  }

  initNewModel(): ProductMeta {
    return new ProductMeta();
  }
  createMeta(item: ProductMeta, index: number) {
    let modalRef = this.modalService.show(MetaDataCreateComponent);
    let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new MetaDataMeta();
    metaData.metaable_type = 'products';
    metaData.metaable_id = item.id;
    modal.setModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<MetaDataMeta>) => {
      if (result.success) {
        this.list[index].meta = result.data;
      }
      sub.unsubscribe();
    });
  }

  editMeta(item: ProductMeta, index: number) {
    let modalRef = this.modalService.show(MetaDataEditComponent);
    let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
    modal.setModel(item.meta);
    let sub = modal.onHidden.subscribe((result: ModalResult<MetaDataMeta>) => {
      if (result.success) {
        this.list[index].meta = result.data;
      }
      sub.unsubscribe();
    });
  }

  categories: Observable<Product_categoryMeta[]>;
  @ViewChild('categorySelector') categorySelector: NgSelectComponent;

  loadAllCategories() {
    this.categories = this.categoryService.loadAll();
  }

  tags: Observable<ProductTagMeta[]>;
  @ViewChild('tagSelector') tagSelector: NgSelectComponent;

  loadAllTags() {
    this.tags = this.tagService.loadAll();
  }

  onTagChange(e: any) {
    this.searchForm.controls['tag_id'].setValue(e.id);
  }

  editTags(item: ProductMeta, index: number) {
    const modalRef = this.modalService.show(ProductTagAssignComponent, {
      'class': 'modal-lg',
      ignoreBackdropClick: true
    });
    let modal: AbstractModalComponent<ProductMeta> = <AbstractModalComponent<ProductMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductMeta>) => {
      if (result.success) {
        let itemEdited: ProductMeta = result.data;
        this.list[index].tags = itemEdited.tags;
      }
      sub.unsubscribe();
    });
  }

  onPublishedChange(item: ProductMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Xuất bản';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }

    methodAsync.subscribe((res: ProductMeta) => {
      this.list[index].published = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }

  openStructured(item: ProductMeta, index: number) {
    let modalRef = this.modalService.show(StructuredDataListComponent, {
      ignoreBackdropClick: true,
      'class': 'modal-huge'
    });
    let modal: AbstractCRUDModalComponent<MetaDataMeta> = <AbstractCRUDModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new StructuredDataMeta();
    metaData.structureble_type = 'products';
    metaData.structureble_id = item.id;
    modal.setRelatedModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<StructuredDataMeta[]>) => {
      if (result.success) {
        this.list[index].structure_datas = result.data;
      }
      sub.unsubscribe();
    });
  }

  openRelated(item: ProductMeta, index: number) {
    let modalRef = this.modalService.show(RelatedProductListComponent, {
      ignoreBackdropClick: true,
      'class': 'modal-huge'
    });
    let modal: AbstractCRUDModalComponent<MetaDataMeta> = <AbstractCRUDModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new RelatedProductMeta();
    metaData.product_id = item.id;
    modal.setRelatedModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<RelatedProductMeta[]>) => {
      if (result.success) {
        this.list[index].related_products = result.data;
      }
      sub.unsubscribe();
    });
  }

  constructor(
    service: ProductService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private categoryService: Product_categoryService,
    private productService: ProductService,
    private metaService: MetaDataService,
    private tagService: ProductTagService,
  ) {
    super(service, modal, builder);
    this.loadAllCategories();
    this.loadAllTags();
  }

  RowSelected(item: any) {
    this.slected = item.id;
  }

  itemSelect = [];

  getItemSelect(ev) {
    if (ev.target.checked == true) {
      this.itemSelect.push(ev.target.value);
    } else {
      for (let i = 0; i < this.itemSelect.length; i++) {
        if (this.itemSelect[i] == ev.target.value) {
          this.itemSelect.splice(i, 1);
        }
      }
    }
  }

  delete() {
    if (this.itemSelect.length == 0) {
      this.productService.toastError('Thất bại', 'Bạn chưa chọn bài viết cần xóa');
    } else {
      this.productService.delete(this.itemSelect).subscribe(() => {
        for (let i = 0; i < this.itemSelect.length; i++) {
          const index = this.list.findIndex(product => product.id == this.itemSelect[i]);
          this.list.splice(index, 1);
        }
        this.itemSelect = [];
        this.productService.toastSuccess('Thành công', 'Xóa bài viết thành công');
      });
    }
  }

  changeOrder(item) {
    this.productService.changeOrder(item).subscribe((res: any) => {
      item = res;
    });
  }

  upOrder(item: ProductMeta, i: number) {
    (<ProductService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.loaded();
    }, () => this.service.toastFailedEdited());
  }

  downOrder(item: ProductMeta, i: number) {
    (<ProductService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.loaded();
    }, () => this.service.toastFailedEdited());
  }
}

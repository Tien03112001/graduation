import {Component, ViewChild} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ProductMeta} from '../product.meta';
import {ProductService} from '../product.service';
import {ProductCreateComponent} from '../product-create/product-create.component';
import {ProductEditComponent} from '../product-edit/product-edit.component';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {AppPagination, ModalResult} from '../../../core/common';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';
import {Observable} from 'rxjs/Observable';
import {NgSelectComponent} from '@ng-select/ng-select';
import {ProductTagAssignComponent} from '../../product-tag/product-tag-assign/product-tag-assign.component';
import {ArticleCommentListComponent} from '../../article-comment/article-comment-list/article-comment-list.component';
import {ArticleCommentMeta} from '../../article-comment/article-comment.meta';
import {StructuredDataMeta} from '../../structured-data/structured-data.meta';
import {StructuredDataListComponent} from '../../structured-data/structured-data-list/structured-data-list.component';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {GalleryImageComponent} from '../../gallery-CRUD/gallery-image/gallery-image.component';
import {ProductTagMeta} from '../../product-tag/product-tag.meta';
import {ProductTagService} from '../../product-tag/product-tag.service';
import {Product_categoryService} from '../../product_category/product_category.service';
import {Product_categoryMeta} from '../../product_category/product_category.meta';
import {ObjectUtil} from '../../../core/utils';
import {GalleryMeta} from '../../gallery/gallery.meta';
import {PageMeta} from '../../page/page.meta';
import {ArticleCreateComponent} from '../../article/article-create/article-create.component';
import {ArticleMeta} from '../../article/article.meta';
import {ArticleEditComponent} from '../../article/article-edit/article-edit.component';
import {GalleryImageMeta} from '../../gallery-CRUD/gallery.meta';
import {GalleryCreateComponent} from '../../gallery/gallery-create/gallery-create.component';
import {ProductVariantMeta} from '../../product-variant/product-variant.meta';
import {ProductVariantListComponent} from '../../product-variant/product-variant-list/product-variant-list.component';


@Component({
  selector: 'app-product',
  templateUrl: './product-list.component.html',
  styleUrls: ['./product-list.component.css'],
  providers: [ProductService, Product_categoryService, MetaDataService, ProductTagService]
})
export class ProductListComponent extends AbstractCRUDComponent<ProductMeta> {
  slected: any;

  onInit(): void {
    this.loaded();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý sản phẩm';
  }

  getCreateModalComponent(): any {
    return ProductCreateComponent;
  }

  getEditModalComponent(): any {
    return ProductEditComponent;
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
  public goToPageNumber() {
    if (this.nextPage <= 0) {
      this.nextPage = 1;
    }
    if (this.nextPage > this.pagination.numPages) {
      this.nextPage = this.pagination.numPages;
    }
    this.pagination.currentPage = this.nextPage;
    this.load();
  }

  public loaded() {
    let params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
      orderBy : 'id:desc'
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

  editMeta(item: ProductMeta) {
    let param = {
      type: 'products',
      id: item.id
    };
    this.metaService.loadByParams(param).subscribe(res => {
      let data = res;
      if (!data) {
        let modalRef = this.modalService.show(MetaDataCreateComponent, {ignoreBackdropClick: true});
        let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
        let metaData = new MetaDataMeta();
        metaData.metaable_type = 'products';
        metaData.metaable_id = item.id;
        modal.setModel(metaData);
      } else {
        let modalRef = this.modalService.show(MetaDataEditComponent);
        let modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
        modal.setModel(data);
      }
    });
  }

  categories: Observable<Product_categoryMeta[]>;
  @ViewChild('categorySelector') categorySelector: NgSelectComponent;

  loadAllCategories() {
    this.categories = this.categoryService.loadAll();
  }

  onCategoryChange(e: any) {
    this.searchForm.controls['category_id'].setValue(e.id);
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
  editSizes(item: ProductVariantMeta, index: number) {
    let modalRef = this.modalService.show(ProductVariantListComponent, {
      ignoreBackdropClick: true,
      'class': 'modal-huge'
    });
    let modal: AbstractCRUDModalComponent<MetaDataMeta> = <AbstractCRUDModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new ProductVariantMeta();
    metaData.product_id = item.id;
    modal.setRelatedModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductVariantMeta>) => {
      if (result.success) {
      }
      sub.unsubscribe();
    });
  }



  openGallery(item: ProductMeta) {
    let modalRef = this.modalService.show(GalleryImageComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<GalleryImageComponent> = <AbstractCRUDModalComponent<GalleryImageComponent>>modalRef.content;
    modal.setRelatedModel(item.gallery);
  }

  manageComments(item: ProductMeta) {
    let modalRef = this.modalService.show(ArticleCommentListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<ArticleCommentMeta> = <AbstractCRUDModalComponent<ArticleCommentMeta>>modalRef.content;
    modal.setRelatedModel(item.article);
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

  createArticle(item: PageMeta, index: number) {
    const modalRef = this.modalService.show(ArticleCreateComponent, {ignoreBackdropClick: true, 'class': 'modal-huge'});
    const modal: AbstractModalComponent<ArticleMeta> = <AbstractModalComponent<ArticleMeta>>modalRef.content;
    const metaData = new ArticleMeta();
    metaData.articleable_type = 'products';
    metaData.articleable_id = item.id;
    modal.setModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<ArticleMeta>) => {
      if (result.success) {
        this.list[index].article = result.data;
        this.load();
      }
      sub.unsubscribe();
    });
  }

  editArticle(item: PageMeta, index: number) {
    const modalRef = this.modalService.show(ArticleEditComponent, {ignoreBackdropClick: true, 'class': 'modal-huge'});
    const modal: AbstractModalComponent<ArticleMeta> = <AbstractModalComponent<ArticleMeta>>modalRef.content;
    modal.setModel(item.article);
    const sub = modal.onHidden.subscribe((result: ModalResult<ArticleMeta>) => {
      if (result.success) {
        this.list[index].article = result.data;
        this.load();
      }
      sub.unsubscribe();
    });
  }

  manageImages(item: ProductMeta) {
    let modalRef = this.modalService.show(GalleryImageComponent, {ignoreBackdropClick: true, class: 'modal-huge'});
    let modal: AbstractCRUDModalComponent<ArticleCommentMeta> = <AbstractCRUDModalComponent<ArticleCommentMeta>>modalRef.content;
    let model = new GalleryImageMeta();
    model.id = item.gallery.id;
    modal.setRelatedModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<GalleryImageMeta[]>) => {
      if (result.success) {
        // item.images = result.data;
      }
      sub.unsubscribe();
    });
  }

  createGallery(item: ProductMeta, index: number) {
    let modalRef = this.modalService.show(GalleryCreateComponent);
    let modal: AbstractModalComponent<GalleryMeta> = <AbstractModalComponent<GalleryMeta>>modalRef.content;
    let model = new GalleryMeta();
    model.galleryable_id = item.id;
    model.galleryable_type = 'products';
    modal.setModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<any>) => {
      if (result.success) {
        item.gallery = result.data;
      }
      sub.unsubscribe();
    });
  }

  public createProduct() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<ProductMeta> = <AbstractModalComponent<ProductMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }

  public editProduct(item, i) {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getEditModalComponentOptions());
    const config = ObjectUtil.combineValue({ ignoreBackdropClick: true }, modalOptions);
    const modalRef = this.modalService.show(this.getEditModalComponent(), config);
    let modal: AbstractModalComponent<ProductMeta> = <AbstractModalComponent<ProductMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<ProductMeta>) => {
      if (result.success) {
        this.load();
      }
    });
  }


}



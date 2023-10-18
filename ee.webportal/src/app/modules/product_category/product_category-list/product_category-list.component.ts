import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {Product_categoryMeta} from '../product_category.meta';
import {Product_categoryService} from '../product_category.service';
import {Product_categoryCreateComponent} from '../product_category-create/product_category-create.component';
import {Product_categoryEditComponent} from '../product_category-edit/product_category-edit.component';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {ObjectUtil} from '../../../core/utils';
import {AppPagination, ModalResult} from '../../../core/common';
import {PageMeta} from 'app/modules/page/page.meta';
import {ArticleCreateComponent} from 'app/modules/article/article-create/article-create.component';
import {ArticleMeta} from 'app/modules/article/article.meta';
import {ArticleEditComponent} from 'app/modules/article/article-edit/article-edit.component';

@Component({
  selector: 'app-product_category',
  templateUrl: './product_category-list.component.html',
  styleUrls: ['./product_category-list.component.css'],
  providers: [Product_categoryService, MetaDataService]
})
export class Product_categoryListComponent extends AbstractCRUDComponent<Product_categoryMeta> {

  onInit(): void {
    this.loaded();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý danh mục sản phẩm';
  }

  getCreateModalComponent(): any {
    return Product_categoryCreateComponent;
  }

  getEditModalComponent(): any {
    return Product_categoryEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): Product_categoryMeta {
    return new Product_categoryMeta();
  }

  editMeta(item: Product_categoryMeta) {
    let param = {
      type: 'post_categories',
      id: item.id
    };
    this.metaService.loadByParams(param).subscribe(res => {
      let data = res;
      if (!data) {
        let modalRef = this.modalService.show(MetaDataCreateComponent, {ignoreBackdropClick: true});
        let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
        let metaData = new MetaDataMeta();
        metaData.metaable_type = 'post_categories';
        metaData.metaable_id = item.id;
        modal.setModel(metaData);
      } else {
        let modalRef = this.modalService.show(MetaDataEditComponent);
        let modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
        modal.setModel(data);
      }
    });
  }

  public create1() {
    let modalOptions = Object.assign(this.defaultModalOptions(), this.getCreateModalComponentOptions());
    const config = ObjectUtil.combineValue({ignoreBackdropClick: true}, modalOptions);
    const modalRef = this.modalService.show(this.getCreateModalComponent(), config);
    let modal: AbstractModalComponent<Product_categoryMeta> = <AbstractModalComponent<Product_categoryMeta>>modalRef.content;
    let metaData = new Product_categoryMeta();
    metaData.parent_id = 0;
    modal.setModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<Product_categoryMeta>) => {
      this.onCreated(result);
      sub.unsubscribe();
    });
  }

  categories: Product_categoryMeta[];

  loadAllCategories() {
    this.service.loadAll().subscribe((res: Product_categoryMeta[]) => {
      this.categories = res;
    }, () => this.categories = []);
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



  onPublishedChange(item: Product_categoryMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Xuất bản';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }

    methodAsync.subscribe((res: Product_categoryMeta) => {
      this.list[index].published = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }

  upOrder(item: Product_categoryMeta, i: number) {
    (<Product_categoryService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.loaded();
    }, () => this.service.toastFailedEdited());
  }

  downOrder(item: Product_categoryMeta, i: number) {
    (<Product_categoryService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.loaded();
    }, () => this.service.toastFailedEdited());
  }

  constructor(
    service: Product_categoryService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private postCategoryService: Product_categoryService,
    private metaService: MetaDataService
  ) {
    super(service, modal, builder);
    this.loadAllCategories();
  }

  changeOrder(item) {
    this.postCategoryService.changeOrder(item).subscribe((res: any) => {
      item = res;
    });
  }



  createArticle(item: PageMeta, index: number) {
    const modalRef = this.modalService.show(ArticleCreateComponent, {ignoreBackdropClick: true, 'class': 'modal-huge'});
    const modal: AbstractModalComponent<ArticleMeta> = <AbstractModalComponent<ArticleMeta>>modalRef.content;
    const metaData = new ArticleMeta();
    metaData.articleable_type = 'product_categories';
    metaData.articleable_id = item.id;
    modal.setModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<ArticleMeta>) => {
      if (result.success) {
        this.list[index].article = result.data;
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
      }
      sub.unsubscribe();
    });
  }
}

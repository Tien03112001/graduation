import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {ObjectUtil} from '../../../core/utils';
import {AppPagination, ModalResult} from '../../../core/common';
import {Product_categoryService} from '../../product_category/product_category.service';
import {Product_categoryMeta} from '../../product_category/product_category.meta';

@Component({
  selector: 'app-product_category',
  templateUrl: './product-category-seometa.component.html',
  styleUrls: ['./product-category-seometa.component.css'],
  providers: [Product_categoryService, MetaDataService]
})
export class ProductCategorySeometaComponent extends AbstractCRUDComponent<Product_categoryMeta> {

  onInit(): void {
    this.loaded();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Tối ưu seo danh mục sản phẩm';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
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

  createMeta(item: Product_categoryMeta, index: number) {
    let modalRef = this.modalService.show(MetaDataCreateComponent);
    let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new MetaDataMeta();
    metaData.metaable_type = 'product_categories';
    metaData.metaable_id = item.id;
    modal.setModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<MetaDataMeta>) => {
      if (result.success) {
        this.list[index].meta = result.data;
      }
      sub.unsubscribe();
    });
  }

  editMeta(item: Product_categoryMeta, index: number) {
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


  constructor(
    service: Product_categoryService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private postCategoryService: Product_categoryService,
    private metaService: MetaDataService
  ) {
    super(service, modal, builder);
  }


}

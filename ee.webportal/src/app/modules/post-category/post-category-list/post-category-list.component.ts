import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {PostCategoryMeta} from '../post-category.meta';
import {PostCategoryService} from '../post-category.service';
import {PostCategoryCreateComponent} from '../post-category-create/post-category-create.component';
import {PostCategoryEditComponent} from '../post-category-edit/post-category-edit.component';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {ObjectUtil} from '../../../core/utils';
import {AppPagination} from '../../../core/common';

@Component({
  selector: 'app-post-category',
  templateUrl: './post-category-list.component.html',
  styleUrls: ['./post-category-list.component.css'],
  providers: [PostCategoryService, MetaDataService]
})
export class PostCategoryListComponent extends AbstractCRUDComponent<PostCategoryMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý danh mục bài viết';
  }

  getCreateModalComponent(): any {
    return PostCategoryCreateComponent;
  }

  getEditModalComponent(): any {
    return PostCategoryEditComponent;
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

  initNewModel(): PostCategoryMeta {
    return new PostCategoryMeta();
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

  editMeta(item: PostCategoryMeta) {
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

  categories: PostCategoryMeta[];

  loadAllCategories() {
    this.service.loadAll().subscribe((res: PostCategoryMeta[]) => {
      this.categories = res;
    }, () => this.categories = []);
  }

  onPublishedChange(item: PostCategoryMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Xuất bản';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }

    methodAsync.subscribe((res: PostCategoryMeta) => {
      this.list[index].published = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }

  upOrder(item: PostCategoryMeta, i: number) {
    (<PostCategoryService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.loaded();
    }, () => this.service.toastFailedEdited());
  }

  downOrder(item: PostCategoryMeta, i: number) {
    (<PostCategoryService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.loaded();
    }, () => this.service.toastFailedEdited());
  }

  constructor(
    service: PostCategoryService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private postCategoryService: PostCategoryService,
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

}

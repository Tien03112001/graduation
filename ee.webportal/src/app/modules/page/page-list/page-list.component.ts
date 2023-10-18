import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {PageMeta} from '../page.meta';
import {PageService} from '../page.service';
import {PageCreateComponent} from '../page-create/page-create.component';
import {PageEditComponent} from '../page-edit/page-edit.component';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {FieldForm, ModalResult} from '../../../core/common';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';
import {ArticleMeta} from '../../article/article.meta';
import {ArticleEditComponent} from '../../article/article-edit/article-edit.component';
import {ArticleCreateComponent} from '../../article/article-create/article-create.component';
import {ArticleCommentMeta} from '../../article-comment/article-comment.meta';
import {StructuredDataListComponent} from '../../structured-data/structured-data-list/structured-data-list.component';
import {StructuredDataMeta} from '../../structured-data/structured-data.meta';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {BlockListComponent} from '../../block/block-list/block-list.component';
import {BlockMeta} from '../../block/block.meta';

@Component({
  selector: 'app-page-list',
  templateUrl: './page-list.component.html',
  styleUrls: ['./page-list.component.css'],
  providers: [PageService, MetaDataService]
})
export class PageListComponent extends AbstractCRUDComponent<PageMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý page';
  }

  getCreateModalComponent(): any {
    return PageCreateComponent;
  }

  getEditModalComponent(): any {
    return PageEditComponent;
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

  initNewModel(): PageMeta {
    return new PageMeta();
  }

  editMeta(item: PageMeta) {
    const param = {
      type: 'pages',
      id: item.id
    };
    this.metaService.loadByParams(param).subscribe(res => {
      if (!res) {
        const modalRef = this.modalService.show(MetaDataCreateComponent, {ignoreBackdropClick: true});
        const modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
        const metaData = new MetaDataMeta();
        metaData.metaable_type = 'post_categories';
        metaData.metaable_id = item.id;
        modal.setModel(metaData);
      } else {
        const modalRef = this.modalService.show(MetaDataEditComponent);
        const modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
        modal.setModel(res);
      }
    });
  }

  openStructured(item: PageMeta, index: number) {
    const modalRef = this.modalService.show(StructuredDataListComponent, {ignoreBackdropClick: true});
    const modal: AbstractCRUDModalComponent<MetaDataMeta> = <AbstractCRUDModalComponent<MetaDataMeta>>modalRef.content;
    const metaData = new StructuredDataMeta();
    metaData.structureble_type = 'pages';
    metaData.structureble_id = item.id;
    modal.setRelatedModel(metaData);
    const sub = modal.onHidden.subscribe((result: ModalResult<StructuredDataMeta[]>) => {
      if (result.success) {
        this.list[index].structure_datas = result.data;
      }
      sub.unsubscribe();
    });
  }

  createArticle(item: PageMeta, index: number) {
    const modalRef = this.modalService.show(ArticleCreateComponent, {ignoreBackdropClick: true, 'class': 'modal-huge'});
    const modal: AbstractModalComponent<ArticleMeta> = <AbstractModalComponent<ArticleMeta>>modalRef.content;
    const metaData = new ArticleMeta();
    metaData.articleable_type = 'pages';
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

  onPublishedChange(item: PageMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg = 'Xuất bản';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }

    methodAsync.subscribe((res: ArticleCommentMeta) => {
      this.list[index].published = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }

  constructor(
    service: PageService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private metaService: MetaDataService
  ) {
    super(service, modal, builder);
  }

  openBlocks(item: PageMeta, index: number) {
    const modalRef = this.modalService.show(BlockListComponent, {class: 'modal-huge', ignoreBackdropClick: true,});
    const modal: AbstractCRUDModalComponent<BlockMeta> = <AbstractCRUDModalComponent<BlockMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<ArticleMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
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
}

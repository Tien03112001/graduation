import {Component, ViewChild} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {PostMeta} from '../post.meta';
import {PostService} from '../post.service';
import {PostCreateComponent} from '../post-create/post-create.component';
import {PostEditComponent} from '../post-edit/post-edit.component';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {ModalResult} from '../../../core/common';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';
import {PostCategoryMeta} from '../../post-category/post-category.meta';
import {Observable} from 'rxjs/Observable';
import {NgSelectComponent} from '@ng-select/ng-select';
import {PostCategoryService} from '../../post-category/post-category.service';
import {PostTagAssignComponent} from '../../post-tag/post-tag-assign/post-tag-assign.component';
import {ArticleCommentListComponent} from '../../article-comment/article-comment-list/article-comment-list.component';
import {ArticleCommentMeta} from '../../article-comment/article-comment.meta';
import {StructuredDataMeta} from '../../structured-data/structured-data.meta';
import {StructuredDataListComponent} from '../../structured-data/structured-data-list/structured-data-list.component';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {GalleryImageComponent} from '../../gallery-CRUD/gallery-image/gallery-image.component';
import {PostTagMeta} from '../../post-tag/post-tag.meta';
import {PostTagService} from '../../post-tag/post-tag.service';

@Component({
  selector: 'app-post',
  templateUrl: './post-list.component.html',
  styleUrls: ['./post-list.component.css'],
  providers: [PostService, PostCategoryService, MetaDataService, PostTagService]
})
export class PostListComponent extends AbstractCRUDComponent<PostMeta> {
  slected: any;

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý bài viết';
  }

  getCreateModalComponent(): any {
    return PostCreateComponent;
  }

  getEditModalComponent(): any {
    return PostEditComponent;
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

  initNewModel(): PostMeta {
    return new PostMeta();
  }

  editMeta(item: PostMeta) {
    let param = {
      type: 'posts',
      id: item.id
    };
    this.metaService.loadByParams(param).subscribe(res => {
      let data = res;
      if (!data) {
        let modalRef = this.modalService.show(MetaDataCreateComponent, {ignoreBackdropClick: true});
        let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
        let metaData = new MetaDataMeta();
        metaData.metaable_type = 'posts';
        metaData.metaable_id = item.id;
        modal.setModel(metaData);
      } else {
        let modalRef = this.modalService.show(MetaDataEditComponent);
        let modal: AbstractModalComponent<any> = <AbstractModalComponent<any>>modalRef.content;
        modal.setModel(data);
      }
    });
  }

  categories: Observable<PostCategoryMeta[]>;
  @ViewChild('categorySelector') categorySelector: NgSelectComponent;

  loadAllCategories() {
    this.categories = this.categoryService.loadAll();
  }

  onCategoryChange(e: any) {
    this.searchForm.controls['category_id'].setValue(e.id);
  }

  tags: Observable<PostTagMeta[]>;
  @ViewChild('tagSelector') tagSelector: NgSelectComponent;

  loadAllTags() {
    this.tags = this.tagService.loadAll();
  }

  onTagChange(e: any) {
    this.searchForm.controls['tag_id'].setValue(e.id);
  }

  editTags(item: PostMeta, index: number) {
    const modalRef = this.modalService.show(PostTagAssignComponent, {'class': 'modal-lg', ignoreBackdropClick: true});
    let modal: AbstractModalComponent<PostMeta> = <AbstractModalComponent<PostMeta>>modalRef.content;
    modal.setModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<PostMeta>) => {
      if (result.success) {
        let itemEdited: PostMeta = result.data;
        this.list[index].tags = itemEdited.tags;
      }
      sub.unsubscribe();
    });
  }

  onPublishedChange(item: PostMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Xuất bản';
    if (enable == true) {
      methodAsync = this.service.enable(item.id);
    }else if(enable == false) {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }

    methodAsync.subscribe((res: PostMeta) => {
      this.list[index].published = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }


  openGallery(item: PostMeta) {
    let modalRef = this.modalService.show(GalleryImageComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<GalleryImageComponent> = <AbstractCRUDModalComponent<GalleryImageComponent>>modalRef.content;
    modal.setRelatedModel(item.gallery);
  }

  manageComments(item: PostMeta) {
    let modalRef = this.modalService.show(ArticleCommentListComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractCRUDModalComponent<ArticleCommentMeta> = <AbstractCRUDModalComponent<ArticleCommentMeta>>modalRef.content;
    modal.setRelatedModel(item.article);
  }

  openStructured(item: PostMeta, index: number) {
    let modalRef = this.modalService.show(StructuredDataListComponent, {ignoreBackdropClick: true, 'class': 'modal-huge'});
    let modal: AbstractCRUDModalComponent<MetaDataMeta> = <AbstractCRUDModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new StructuredDataMeta();
    metaData.structureble_type = 'posts';
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
    service: PostService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private categoryService: PostCategoryService,
    private postService: PostService,
    private metaService: MetaDataService,
    private tagService: PostTagService,
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
      this.postService.toastError('Thất bại', 'Bạn chưa chọn bài viết cần xóa');
    } else {
      this.postService.delete(this.itemSelect).subscribe(() => {
        for (let i = 0; i < this.itemSelect.length; i++) {
          const index = this.list.findIndex(post => post.id == this.itemSelect[i]);
          this.list.splice(index, 1);
        }
        this.itemSelect = [];
        this.postService.toastSuccess('Thành công', 'Xóa bài viết thành công');
      });
    }
  }

  changeOrder(item) {
    this.postService.changeOrder(item).subscribe((res: any) => {
      item = res;
    });
  }

  upOrder(item: PostMeta, i: number) {
    (<PostService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  downOrder(item: PostMeta, i: number) {
    (<PostService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
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

import {Component} from '@angular/core';
import {AbstractCRUDModalComponent} from '../../../core/crud';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {ArticleCommentService} from '../article-comment.service';
import {ArticleCommentMeta} from '../article-comment.meta';
import {ArticleCommentCreateComponent} from '../article-comment-create/article-comment-create.component';
import {ArticleCommentEditComponent} from '../article-comment-edit/article-comment-edit.component';
import {ObjectUtil} from '../../../core/utils';
import {AppPagination} from '../../../core/common';

@Component({
  selector: 'app-article-comment',
  templateUrl: './article-comment-list.component.html',
  styleUrls: ['./article-comment-list.component.css'],
  providers: [ArticleCommentService]
})
export class ArticleCommentListComponent extends AbstractCRUDModalComponent<ArticleCommentMeta> {
  article_id: number = 0;

  onInit(): void {
  }

  onDestroy(): void {
  }

  getCreateModalComponent(): any {
    return ArticleCommentCreateComponent;
  }

  getEditModalComponent(): any {
    return ArticleCommentEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-small'};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-small'};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      article_id: new FormControl(null, Validators.required)
    });
  }

  initNewModel(): ArticleCommentMeta {
    const model = new ArticleCommentMeta();
    model.article_id = this.relatedModel.id;
    model.parent_id = 0;
    return model;
  }

  repply(item) {
    const modalRef = this.modalService.show(ArticleCommentCreateComponent, {
      ignoreBackdropClick: true,
      class: 'modal-lg'
    });
    const modal: AbstractCRUDModalComponent<ArticleCommentMeta> = <AbstractCRUDModalComponent<ArticleCommentMeta>>modalRef.content;
    const model = new ArticleCommentMeta();
    model.parent_id = item.id;
    return model;
  }

  constructor(
    service: ArticleCommentService,
    modal: BsModalRef,
    modalService: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {

    super(service, modal, modalService, builder);
  }

  loaded(): void {
    this.searchForm.controls['article_id'].setValue(this.relatedModel.id);
    const params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    }, ObjectUtil.ignoreNullValue(this.searchForm.value));
    this.service.loadByPage(params).subscribe((data: any) => {
        this.list = data.data;
        this.pagination.set(data);
      }, () => {
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
      }
    );
  }

  getTitle(): string {
    return "Quản lý bình luận bài viết";
  }

  onPublishedChange(item: ArticleCommentMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Đã duyệt';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Chờ duyệt';
    }

    methodAsync.subscribe((res: ArticleCommentMeta) => {
      this.list[index].published = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }

}

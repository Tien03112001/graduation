import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {GalleryMeta} from '../../gallery-CRUD/gallery.meta';
import {GalleryService} from '../../gallery-CRUD/gallery.service';
import {ArticleCommentMeta} from '../../article-comment/article-comment.meta';
import {GalleryImageComponent} from '../../gallery-CRUD/gallery-image/gallery-image.component';
import {ModalResult} from '../../../core/common';
import {PhotoMeta} from '../../photo-CRUD/photo.meta';
import {GalleryEditComponent} from '../../gallery-CRUD/gallery-edit/gallery-edit.component';
import {GalleryCreateComponent} from '../../gallery-CRUD/gallery-create/gallery-create.component';

@Component({
  selector: 'app-gallery',
  templateUrl: './gallery-list.component.html',
  styleUrls: ['./gallery-list.component.css'],
  providers: [GalleryService]
})
export class GalleryListComponent extends AbstractCRUDComponent<GalleryMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý album ảnh';
  }

  getCreateModalComponent(): any {
    return GalleryCreateComponent;
  }

  getEditModalComponent(): any {
    return GalleryEditComponent;
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
    });
  }

  initNewModel(): GalleryMeta {
    return new GalleryMeta();
  }


  onPublishedChange(item: GalleryMeta, index: number, enable: boolean) {
    let methodAsync = null;
    let titleMsg: string = 'Xuất bản';
    if (enable) {
      methodAsync = this.service.enable(item.id);
    } else {
      methodAsync = this.service.disable(item.id);
      titleMsg = 'Lưu kho';
    }

    methodAsync.subscribe((res: GalleryMeta) => {
      this.list[index].published = res.published;
      this.service.toastSuccessfully(titleMsg);
    }, () => this.service.toastFailed(titleMsg));
  }

  manageImages(item: GalleryMeta) {
    let modalRef = this.modalService.show(GalleryImageComponent, {ignoreBackdropClick: true, class: 'modal-huge'});
    let modal: AbstractCRUDModalComponent<ArticleCommentMeta> = <AbstractCRUDModalComponent<ArticleCommentMeta>>modalRef.content;
    modal.setRelatedModel(item);
    let sub = modal.onHidden.subscribe((result: ModalResult<PhotoMeta[]>) => {
      if (result.success) {
        // item.images = result.data;
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

  constructor(
    service: GalleryService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

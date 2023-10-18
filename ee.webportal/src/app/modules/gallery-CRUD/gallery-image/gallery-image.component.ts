import {Component} from '@angular/core';
import {AbstractCRUDModalComponent, AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {PhotoMeta} from '../../photo-CRUD/photo.meta';
import {AppPagination, ModalResult} from '../../../core/common';
import {GalleryImageService} from '../gallery.service';
import {GalleryImageMeta} from '../gallery.meta';
import {GalleryImageCreateComponent} from '../gallery-create/gallery-create.component';
import {GalleryImageEditComponent} from '../gallery-edit/gallery-edit.component';

@Component({
  selector: 'app-gallery-image',
  templateUrl: './gallery-image.component.html',
  styleUrls: ['./gallery-image.component.css'],
  providers: [GalleryImageService]
})
export class GalleryImageComponent extends AbstractCRUDModalComponent<GalleryImageMeta> {

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  getCreateModalComponent(): any {
    return GalleryImageCreateComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponent(): any {
    return GalleryImageEditComponent;
  }

  initNewModel(): GalleryImageMeta {
    const newModel: GalleryImageMeta = new GalleryImageMeta();
    newModel.gallery_id = this.relatedModel.id;
    return newModel;
  }

  onInit(): void {
  }

  onDestroy(): void {
  }


  loaded(): void {
    this.load();
  }

  constructor(
    service: GalleryImageService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modalRef, modal, builder);
  }

  public load() {
    let param = {
      gallery_id: this.relatedModel.id,
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
    };
    this.service.loadByPage(param).subscribe((res: any) => {
        this.nextPage = this.pagination.currentPage;
        this.list = res.data;
        this.pagination.set(res);
      }, () => {
        this.list = [];
        this.pagination = new AppPagination();
        this.nextPage = this.pagination.currentPage;
      }
    );
  }

  dismiss() {
    this.onHidden.emit(new ModalResult<PhotoMeta[]>(this.list));
    this.modal.hide();
  }

  public create() {
    const modalRef = this.modalService.show(this.getCreateModalComponent(), this.getCreateModalComponentOptions());
    let modal: AbstractModalComponent<GalleryImageMeta> = <AbstractModalComponent<GalleryImageMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<GalleryImageMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  getTitle(): string {
    return 'Quản lý ảnh trong album';
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

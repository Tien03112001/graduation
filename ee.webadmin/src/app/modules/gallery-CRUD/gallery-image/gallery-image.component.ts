import {Component} from '@angular/core';
import {AbstractCRUDModalComponent} from '../../../core/crud';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {GalleryService} from '../gallery.service';
import {PhotoCreateComponent} from '../../photo-CRUD/photo-create/photo-create.component';
import {PhotoEditComponent} from '../../photo-CRUD/photo-edit/photo-edit.component';
import {PhotoMeta} from '../../photo-CRUD/photo.meta';
import {PhotoService} from '../../photo-CRUD/photo.service';
import {ModalResult} from '../../../core/common';
import {AbstractModalComponent} from '../../../core/crud';

@Component({
  selector: 'app-gallery-image',
  templateUrl: './gallery-image.component.html',
  styleUrls: ['./gallery-image.component.css'],
  providers: [GalleryService, PhotoService]
})
export class GalleryImageComponent extends AbstractCRUDModalComponent<PhotoMeta> {

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  getCreateModalComponent(): any {
    return PhotoCreateComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponent(): any {
    return PhotoEditComponent;
  }

  initNewModel(): PhotoMeta {
    const newModel: PhotoMeta = new PhotoMeta();
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
    service: PhotoService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
    private galleryService: GalleryService
  ) {
    super(service, modalRef, modal, builder);
  }

  public load() {
    this.galleryService.images(this.relatedModel.id).subscribe((res: PhotoMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }

  dismiss() {
    this.onHidden.emit(new ModalResult<PhotoMeta[]>(this.list));
    this.modal.hide();
  }

  public create() {
    const modalRef = this.modalService.show(this.getCreateModalComponent(), this.getCreateModalComponentOptions());
    let modal: AbstractModalComponent<PhotoMeta> = <AbstractModalComponent<PhotoMeta>>modalRef.content;
    modal.setModel(this.initNewModel());
    let sub = modal.onHidden.subscribe((result: ModalResult<PhotoMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  getTitle(): string {
    return 'Quản lý ảnh trong album';
  }

  upOrder(item: PhotoMeta, i: number) {
    (<PhotoService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  downOrder(item: PhotoMeta, i: number) {
    (<PhotoService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

}

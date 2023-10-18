import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {PhotoCreateComponent} from '../../photo-CRUD/photo-create/photo-create.component';
import {PhotoMeta} from '../../photo-CRUD/photo.meta';
import {PhotoEditComponent} from '../../photo-CRUD/photo-edit/photo-edit.component';
import {TitleService} from '../../../core/services';
import {PhotoService} from '../../photo-CRUD/photo.service';
import {AppPagination, ModalResult} from '../../../core/common';
import {ObjectUtil} from '../../../core/utils';

@Component({
  selector: 'app-photos',
  templateUrl: './photo-list.component.html',
  styleUrls: ['./photo-list.component.css'],
  providers: [PhotoService]
})
export class PhotoListComponent extends AbstractCRUDComponent<PhotoMeta> {

  onInit(): void {
    this.loaded();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý Ảnh';
  }

  getCreateModalComponent(): any {
    return PhotoCreateComponent;
  }

  getEditModalComponent(): any {
    return PhotoEditComponent;
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

  initNewModel(): PhotoMeta {
    return new PhotoMeta();
  }

  public loaded() {
    let params: any = ObjectUtil.combineValue({
      limit: this.pagination.itemsPerPage,
      page: this.pagination.currentPage,
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

  createPhoto() {
    const modalRef = this.modalService.show(PhotoCreateComponent, {ignoreBackdropClick: true});
    const modal: AbstractModalComponent<PhotoMeta> = <AbstractModalComponent<PhotoMeta>>modalRef.content;
    const photoMeta = new PhotoMeta();
    modal.setModel(photoMeta);
    const sub = modal.onHidden.subscribe((result: ModalResult<PhotoMeta>) => {
      if (result.success) {
        this.load();
      }
      sub.unsubscribe();
    });
  }

  copy(copy_content: string) {
    const selBox = document.createElement('textarea');
    selBox.style.position = 'fixed';
    selBox.style.left = '0';
    selBox.style.top = '0';
    selBox.style.opacity = '0';
    selBox.value = copy_content;
    document.body.appendChild(selBox);
    selBox.focus();
    selBox.select();
    document.execCommand('copy');
    document.body.removeChild(selBox);
    this.service.toastSuccess('Copy url: ' + copy_content);
  }

  constructor(
    service: PhotoService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
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

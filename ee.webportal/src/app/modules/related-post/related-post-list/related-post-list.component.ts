import {Component} from '@angular/core';
import {AbstractCRUDModalComponent} from '../../../core/crud';
import {PhotoMeta} from '../../photo-CRUD/photo.meta';
import {FormBuilder, FormGroup} from '@angular/forms';
import {BsModalRef, BsModalService, ModalOptions} from 'ngx-bootstrap';
import {ModalResult} from '../../../core/common';
import {RelatedPostMeta} from '../related-post.meta';
import {RelatedPostService} from '../related-post.service';
import {RelatedPostCreateComponent} from '../related-post-create/related-post-create.component';


@Component({
  selector: 'app-related-post',
  templateUrl: './related-post-list.component.html',
  styleUrls: ['./related-post-list.component.css'],
  providers: [RelatedPostService]
})
export class RelatedPostListComponent extends AbstractCRUDModalComponent<RelatedPostMeta> {

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({});
  }

  getCreateModalComponent(): any {
    return RelatedPostCreateComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  public getEditModalComponent() {
    throw new Error('Method not implemented.');
  }

  getEditModalComponentOptions(): ModalOptions {

    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  initNewModel(): RelatedPostMeta {
    let newModel: RelatedPostMeta = new RelatedPostMeta();
    newModel.post_id = this.relatedModel.post_id;
    newModel.related_id = this.relatedModel.related_id;
    newModel.existsRelations = this.list;
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
    service: RelatedPostService,
    modalRef: BsModalRef,
    modal: BsModalService,
    builder: FormBuilder,
  ) {
    super(service, modalRef, modal, builder);
  }

  public load() {
    let param = {
      post_id: this.relatedModel.post_id,
    };
    this.service.loadByParams(param).subscribe((res: RelatedPostMeta[]) => {
        this.list = res;
      }, () => {
        this.list = [];
      }
    );
  }

  upOrder(item: RelatedPostMeta, i: number) {
    (<RelatedPostService>this.service).up(item.id).subscribe(res => {
      this.service.toastSuccessfully('Tăng thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  downOrder(item: RelatedPostMeta, i: number) {
    (<RelatedPostService>this.service).down(item.id).subscribe(res => {
      this.service.toastSuccessfully('Giảm thứ tự');
      this.load();
    }, () => this.service.toastFailedEdited());
  }

  dismiss(): void {
    this.onHidden.emit(new ModalResult<PhotoMeta[]>(this.list));
    this.modal.hide();
  }

  getTitle(): string {
    return "";
  }

}

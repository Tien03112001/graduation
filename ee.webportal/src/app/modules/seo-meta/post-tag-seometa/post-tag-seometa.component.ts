import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {ModalResult} from '../../../core/common';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';
import {PostTagService} from '../../post-tag/post-tag.service';
import {PostTagMeta} from '../../post-tag/post-tag.meta';

@Component({
  selector: 'app-post-tag',
  templateUrl: './post-tag-seometa.component.html',
  styleUrls: ['./post-tag-seometa.component.css'],
  providers: [PostTagService]
})
export class PostTagSeometaComponent extends AbstractCRUDComponent<PostTagMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Tối ưu seo tag bài viết';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {class: 'modal-lg', ignoreBackdropClick: true};
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initNewModel(): PostTagMeta {
    return new PostTagMeta();
  }

  createMeta(item: PostTagMeta, index: number) {
    let modalRef = this.modalService.show(MetaDataCreateComponent);
    let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new MetaDataMeta();
    metaData.metaable_type = 'post_tags';
    metaData.metaable_id = item.id;
    modal.setModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<MetaDataMeta>) => {
      if (result.success) {
        this.list[index].meta = result.data;
      }
      sub.unsubscribe();
    });
  }

  editMeta(item: PostTagMeta, index: number) {
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

  constructor(
    service: PostTagService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

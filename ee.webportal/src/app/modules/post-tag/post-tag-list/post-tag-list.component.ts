import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {PostTagMeta} from '../post-tag.meta';
import {PostTagService} from '../post-tag.service';
import {PostTagCreateComponent} from '../post-tag-create/post-tag-create.component';
import {PostTagEditComponent} from '../post-tag-edit/post-tag-edit.component';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {ModalResult} from '../../../core/common';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';

@Component({
  selector: 'app-post-tag',
  templateUrl: './post-tag-list.component.html',
  styleUrls: ['./post-tag-list.component.css'],
  providers: [PostTagService]
})
export class PostTagListComponent extends AbstractCRUDComponent<PostTagMeta> {

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Quản lý tag bài viết';
  }

  getCreateModalComponent(): any {
    return PostTagCreateComponent;
  }

  getEditModalComponent(): any {
    return PostTagEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
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
    service: PostTagService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

}

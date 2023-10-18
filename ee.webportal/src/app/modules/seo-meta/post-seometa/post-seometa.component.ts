import {Component} from '@angular/core';
import {AbstractCRUDComponent, AbstractCRUDModalComponent, AbstractModalComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {MetaDataEditComponent} from '../../meta-data/meta-data-edit/meta-data-edit.component';
import {MetaDataMeta} from '../../meta-data/meta-data.meta';
import {ModalResult} from '../../../core/common';
import {MetaDataCreateComponent} from '../../meta-data/meta-data-create/meta-data-create.component';
import {PostCategoryService} from '../../post-category/post-category.service';
import {StructuredDataMeta} from '../../structured-data/structured-data.meta';
import {StructuredDataListComponent} from '../../structured-data/structured-data-list/structured-data-list.component';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {PostTagService} from '../../post-tag/post-tag.service';
import {PostService} from '../../post/post.service';
import {PostMeta} from '../../post/post.meta';
import { RelatedPostListComponent } from '../../related-post/related-post-list/related-post-list.component';
import { RelatedPostMeta } from '../../related-post/related-post.meta';

@Component({
  selector: 'app-post',
  templateUrl: './post-seometa.component.html',
  styleUrls: ['./post-seometa.component.css'],
  providers: [PostService, PostCategoryService, MetaDataService, PostTagService]
})
export class PostSeometaComponent extends AbstractCRUDComponent<PostMeta> {
  slected: any;

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Tối ưu seo bài viết';
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

  createMeta(item: PostMeta, index: number) {
    let modalRef = this.modalService.show(MetaDataCreateComponent);
    let modal: AbstractModalComponent<MetaDataMeta> = <AbstractModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new MetaDataMeta();
    metaData.metaable_type = 'posts';
    metaData.metaable_id = item.id;
    modal.setModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<MetaDataMeta>) => {
      if (result.success) {
        this.list[index].meta = result.data;
      }
      sub.unsubscribe();
    });
  }

  editMeta(item: PostMeta, index: number) {
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

  openStructured1(item: PostMeta, index: number) {
    let modalRef = this.modalService.show(StructuredDataListComponent, {
      ignoreBackdropClick: true,
      'class': 'modal-huge'
    });
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

  openRelated1(item: PostMeta, index: number) {
    let modalRef = this.modalService.show(RelatedPostListComponent, {
      ignoreBackdropClick: true,
      'class': 'modal-huge'
    });
    let modal: AbstractCRUDModalComponent<MetaDataMeta> = <AbstractCRUDModalComponent<MetaDataMeta>>modalRef.content;
    let metaData = new RelatedPostMeta();
    metaData.post_id = item.id;
    modal.setRelatedModel(metaData);
    let sub = modal.onHidden.subscribe((result: ModalResult<RelatedPostMeta[]>) => {
      if (result.success) {
        this.list[index].related_posts = result.data;
      }
      sub.unsubscribe();
    });
  }

  constructor(
    service: PostService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }
}

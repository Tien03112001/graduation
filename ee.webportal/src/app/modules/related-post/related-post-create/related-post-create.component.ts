import { Component } from '@angular/core';
import { AbstractModalComponent } from '../../../core/crud';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { RelatedPostMeta } from '../related-post.meta';
import { RelatedPostService } from '../related-post.service';
import { FieldForm } from '../../../core/common';
import { ObjectUtil } from '../../../core/utils';
import { PostService } from '../../post/post.service';

@Component({
  selector: 'app-related-post-create',
  templateUrl: './related-post-create.component.html',
  styleUrls: ['./related-post-create.component.css'],
  providers: [RelatedPostService, PostService]
})
export class RelatedPostCreateComponent extends AbstractModalComponent<RelatedPostMeta> {
  ckEditorConfig: any = {
    height: '350px'
  };

  public posts = [];
  ObjectUtil: any;

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
    });
  }

  initFieldForm(): FieldForm[] {
    return [];
  }

  loaded(): void {
    this.load();
  }

  formSearchPost: FormGroup = new FormGroup({
    search: new FormControl(),
  })

  constructor(
    service: RelatedPostService,
    modal: BsModalRef,
    builder: FormBuilder,
    private postService: PostService,
  ) {
    super(service, modal, builder);
  }

  public load() {
    let relatedPosts = this.model.existsRelations;
    this.postService.loadAll().subscribe((posts) => {
      this.posts = posts.filter(value => {
        let relatedExists = relatedPosts.filter(v => v.related_id == value.id);
        if (relatedExists.length == 0) {
          return value.id != this.model.post_id;
        }
        return false;
      });
    });
  }

  createPost(p) {
    let item = ObjectUtil.ignoreNullValue(this.model);
    const data = { post_id: item.post_id, related_id: p.id }
    this.service.storeRelated(data).subscribe(res => {
      this.service.toastSuccessfullyCreated();
      this.close(res);
    }, () => this.service.toastFailedCreated());
  }

  searchPost() {
    let relatedPosts = this.model.existsRelations;
    let params: any = ObjectUtil.combineValue({}, this.formSearchPost.value, true);
    this.postService.loadByParams(params).subscribe((posts) => {
      this.posts = posts.filter(value => {
        let relatedExists = relatedPosts.filter(v => v.related_id == value.id);
        if (relatedExists.length == 0) {
          return value.id != this.model.post_id;
        }
        return false;
      });
    });
  }
}

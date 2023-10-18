import {Component, ViewChild} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {PostMeta} from '../../post/post.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PostService} from '../../post/post.service';
import {Observable} from 'rxjs/Observable';
import {NgSelectComponent} from '@ng-select/ng-select';
import {PostTagService} from '../post-tag.service';
import {PostTagMeta} from '../post-tag.meta';
import {ObjectUtil} from '../../../core/utils';

@Component({
  selector: 'app-post-tag-assign',
  templateUrl: './post-tag-assign.component.html',
  styleUrls: ['./post-tag-assign.component.css'],
  providers: [PostService, PostTagService]
})
export class PostTagAssignComponent extends AbstractModalComponent<PostMeta> {


  tags: Observable<PostTagMeta[]>;
  @ViewChild('tagsSelector') tagsSelector: NgSelectComponent;

  loadAllTags() {
    this.tags = this.tagService.loadAll();
  }

  setTags(tags: PostTagMeta[]) {
    if (tags && tags.length > 0) {
      tags.forEach(v => {
        this.tagsSelector.select({value: v, label: v.name});
      });
    }
  }

  onTagsChange(e: any) {
    if (e) {
      let ids: number[] = [];
      e.forEach(v => {
        ids.push(v.id);
      });
      this.formGroup.controls['ids'].setValue(ids);
    }
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      ids: new FormControl(null, Validators.required),
    });
  }

  loaded(): void {
    this.setTags(this.model.tags);
  }

  assign() {
    let ids: number[] = this.formGroup.controls['ids'].value;
    (<PostService>this.service).assignTags(this.model.id, ids).subscribe((res: PostMeta) => {
      this.service.toastSuccessfullyEdited();
      this.close(ObjectUtil.mergeValue(this.model, res));
    }, () => this.service.toastFailedEdited());
  }

  constructor(
    service: PostService,
    modal: BsModalRef,
    builder: FormBuilder,
    private tagService: PostTagService
  ) {
    super(service, modal, builder);
    this.loadAllTags();
  }

}

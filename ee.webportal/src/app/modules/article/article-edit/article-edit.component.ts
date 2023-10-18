import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ArticleService} from '../article.service';
import {ArticleMeta} from '../article.meta';

@Component({
  selector: 'app-article-edit',
  templateUrl: './article-edit.component.html',
  styleUrls: ['./article-edit.component.css'],
  providers: [ArticleService]
})
export class ArticleEditComponent extends AbstractModalComponent<ArticleMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      title: new FormControl(null),
      content: new FormControl(null, Validators.required),
      articleable_type: new FormControl(null, Validators.required),
      articleable_id: new FormControl(null, Validators.required),
    });
  }

  loaded(): void {
    this.formGroup.setValue({
      title: this.model.title,
      content: this.model.content,
      articleable_type: this.model.articleable_type,
      articleable_id: this.model.articleable_id,
    });
  }


  constructor(
    service: ArticleService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

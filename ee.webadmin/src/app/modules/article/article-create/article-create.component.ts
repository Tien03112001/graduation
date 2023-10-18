import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ArticleService} from '../article.service';
import {ArticleMeta} from '../article.meta';

@Component({
  selector: 'app-article-create',
  templateUrl: './article-create.component.html',
  styleUrls: ['./article-create.component.css'],
  providers: [ArticleService]
})
export class ArticleCreateComponent extends AbstractModalComponent<ArticleMeta> {

  ckEditorConfig: any = {
    height: '350px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      title: new FormControl(null, Validators.required),
      content: new FormControl(null, Validators.required),
      author_name: new FormControl(null),
      author_url: new FormControl(null),
      articleable_type: new FormControl(null, Validators.required),
      articleable_id: new FormControl(null, Validators.required),
    });
  }

  loaded(): void {
    this.formGroup.setValue({
      title: null,
      content: null,
      author_name: null,
      author_url: null,
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

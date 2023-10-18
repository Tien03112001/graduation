import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ArticleCommentService} from '../article-comment.service';
import {ArticleCommentMeta} from '../article-comment.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-article-comment-edit',
  templateUrl: './article-comment-edit.component.html',
  styleUrls: ['./article-comment-edit.component.css'],
  providers: [ArticleCommentService]
})
export class ArticleCommentEditComponent extends AbstractModalComponent<ArticleCommentMeta> {

  ckEditorConfig: any = {
    height: '200px'
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      author: new FormControl(null,[Validators.required, Validators.pattern('^(?=.*[a-zA-Z]+)[^-\\s][a-zA-Z0-9~`!@#$%^&*()+=?:;"\'_ÂÀÁÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ ]*$')]),
      content: new FormControl(null, Validators.required),
      parent_id: new FormControl(0),
      article_id: new FormControl(null),
      reserve_at: new FormControl(null),
      published: new FormControl(true),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên khách hàng *', 'author', 'Nhập tên khách hàng'),
      FieldForm.createDateTimeInput('Đặt lịch', 'reserve_at', 'Chọn lịch đặt'),
      FieldForm.createHtmlInput('Bình luận *', 'content', {height: '200px'}),
    ];
  }

  loaded(): void {
    this.formGroup.setValue({
      author: this.model.author,
      content: this.model.content,
      published: this.model.published,
      reserve_at: this.model.reserve_at,
      parent_id: this.model.parent_id,
      article_id: this.model.article_id
    });
  }

  constructor(
    service: ArticleCommentService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

}

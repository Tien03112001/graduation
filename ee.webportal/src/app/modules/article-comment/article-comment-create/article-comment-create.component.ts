import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {ArticleCommentMeta} from '../article-comment.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {ArticleCommentService} from '../article-comment.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-article-comment-create',
  templateUrl: './article-comment-create.component.html',
  styleUrls: ['./article-comment-create.component.css'],
  providers: [ArticleCommentService]
})
export class ArticleCommentCreateComponent extends AbstractModalComponent<ArticleCommentMeta> {

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
    this.formGroup.controls['article_id'].setValue(this.model.article_id);
  }


  constructor(
    service: ArticleCommentService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }


}

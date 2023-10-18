import {Component} from '@angular/core';
import {PostMeta} from '../post.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef, BsModalService} from 'ngx-bootstrap';
import {PostService} from '../post.service';
import {PostCategoryMeta} from '../../post-category/post-category.meta';
import {PostCategoryService} from '../../post-category/post-category.service';
import {AbstractModalComponent} from '../../../core/crud';
import {PostCreateCategoryComponent} from '../post-create-category/post-create-category.component';
import {FieldForm, ModalResult} from '../../../core/common';

@Component({
  selector: 'app-post-create',
  templateUrl: './post-create.component.html',
  styleUrls: ['./post-create.component.css'],
  providers: [PostService, PostCategoryService, BsModalService]
})
export class PostCreateComponent extends AbstractModalComponent<PostMeta> {
  img: any = null;
  ckEditorConfig: any = {
    height: '350px'
  };

  loadAllCategories() {
    return this.categoryService.loadAll();
  }

  onCategoryChange(e: any) {
    if (e) {
      this.formGroup.controls['category_id'].setValue(e.id);
      this.formGroup.controls['category_slug'].setValue(e.slug);
    }
  }

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required,Validators.pattern('^(?=.*[a-zA-Z]+)[^-\].*$')]),
      content: new FormControl(null, Validators.required),
      category_id: new FormControl(null),
      category_slug: new FormControl(null),
      summary: new FormControl(null),
      image: new FormControl(null),
      published: new FormControl(true),
      reserve_at: new FormControl(null),
      category: new FormControl(null, Validators.required)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect2('Danh mục ', 'category', 'Chọn danh mục', 'loadAllCategories'),
      FieldForm.createTextInput('Tên bài viết ', 'name', 'Nhập tên bài viết'),
      FieldForm.createFileInput('Ảnh đại diện', 'image', 'Chọn ảnh', this.onImageUploadChange, 'image/*'),
      FieldForm.createDateTimeInput('Đặt lịch', 'reserve_at', 'Chọn lịch đặt'),
      FieldForm.createHtmlInput('Tóm tắt', 'summary', {height: '100px'}),
      FieldForm.createHtmlInput('Nội dung ', 'content', {height: '200px'}),
    ];
  }

  loaded(): void {
  }

  constructor(
    service: PostService,
    modal: BsModalRef,
    builder: FormBuilder,
    private categoryService: PostCategoryService,
    private modalService: BsModalService,
  ) {
    super(service, modal, builder);
  }

  createCategory() {
    let modalRef = this.modalService.show(PostCreateCategoryComponent, {ignoreBackdropClick: true, class: 'modal-lg'});
    let modal: AbstractModalComponent<PostCategoryMeta> = <AbstractModalComponent<PostCategoryMeta>>modalRef.content;
    let model = new PostCategoryMeta();
    modal.setModel(model);
    let sub = modal.onHidden.subscribe((result: ModalResult<PostCategoryMeta>) => {
      if (result.success) {
        this.fields[0].data.push(result.data);
      }
      sub.unsubscribe();
    });
  }

  createObjWithImage() {
    if (this.formGroup.controls['published'].value) {
      this.formGroup.controls['published'].setValue(1);
    } else {
      this.formGroup.controls['published'].setValue(0);
    }
    // this.formGroup.controls['label'].setValue(JSON.stringify(this.formGroup.controls['labels'].value));
    if(this.formGroup.controls['category'].value) {
      this.formGroup.controls['category_id'].setValue(this.formGroup.controls['category'].value[0].id)
      this.formGroup.controls['category_slug'].setValue(this.formGroup.controls['category'].value[0].slug)
    }
    this.createWithImage();
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }
}

import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {PostCategoryMeta} from '../post-category.meta';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PostCategoryService} from '../post-category.service';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-post-category-create',
  templateUrl: './post-category-create.component.html',
  styleUrls: ['./post-category-create.component.css'],
  providers: [PostCategoryService]
})
export class PostCategoryCreateComponent extends AbstractModalComponent<PostCategoryMeta> {


  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      category: new FormControl(null),
      name: new FormControl(null, [Validators.required, Validators.maxLength(255), Validators.pattern('^(?=.*[a-zA-Z]+)[^-\].*$')]),
      published: new FormControl(true),
      parent_id: new FormControl(0),
      summary: new FormControl(null),
      label: new FormControl([]),
      meta_title: new FormControl(null),
      meta_keyword: new FormControl(null),
      meta_description: new FormControl(null),
      image: new FormControl(null),
      alt: new FormControl(null)
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tên', 'name', 'Nhập tên'),
      FieldForm.createHtmlInput('Tóm tắt', 'summary', 'Nhập ký tự'),
    ];
  }

  loadAllCategories() {
    return this.service.loadAll();
  }

  loaded(): void {
  }


  constructor(
    service: PostCategoryService,
    modal: BsModalRef,
    builder: FormBuilder
  ) {
    super(service, modal, builder);
  }

  onImageUploadChange(formGroup: FormGroup, controlName: string, event: any) {
    const input = event.target;
    if (input.files && input.files[0]) {
      formGroup.controls[controlName].setValue(input.files[0]);
    }
  }
}

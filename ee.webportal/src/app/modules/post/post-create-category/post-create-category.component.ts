import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {PostCategoryService} from '../../post-category/post-category.service';
import {PostCategoryMeta} from '../../post-category/post-category.meta';
import {FieldForm} from '../../../core/common';

@Component({
  selector: 'app-post-category-create',
  templateUrl: './post-create-category.component.html',
  styleUrls: ['./post-create-category.component.css'],
  providers: [PostCategoryService]
})
export class PostCreateCategoryComponent extends AbstractModalComponent<PostCategoryMeta> {
  img: any = null;
  categories: PostCategoryMeta[] = [];
  ckEditorConfig: any = {
    height: '250px'
  };
  selectSetting = {
    text: 'Chọn nhãn',
    labelKey: 'name',
    searchBy: ['name'],
    enableSearchFilter: true,
    singleSelection: false,
  };

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, [Validators.required, Validators.pattern('^(?=.*[a-zA-Z]+)[^-\].*$'), Validators.maxLength(255)]),
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
    this.service.loadAll().subscribe((res: PostCategoryMeta[]) => {
      this.categories = res;
    }, () => this.categories = []);
  }

  loaded(): void {
  }

  readImage(event: any) {
    let input = event.target;
    if (input.files && input.files[0]) {
      let reader = new FileReader();
      this.img = input.files[0];
      reader.onload = (e: any) => {
        (<HTMLImageElement>document.getElementById('imgDemoCategory')).src = e.target.result;
        this.formGroup.controls['image'].setValue(input.files[0]);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  constructor(
    service: PostCategoryService,
    modal: BsModalRef,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
    this.loadAllCategories();
  }

  onParentChange(e) {
    this.formGroup.controls['parent_id'].setValue(e);
  }

  createObjWitImage() {
    this.createWithImage();
  }
}

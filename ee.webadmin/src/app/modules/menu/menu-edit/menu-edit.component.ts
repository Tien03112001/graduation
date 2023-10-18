import {Component} from '@angular/core';
import {AbstractModalComponent} from '../../../core/crud';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {BsModalRef} from 'ngx-bootstrap';
import {MenuService} from '../menu.service';
import {MenuMeta} from '../menu.meta';
import {FieldForm} from '../../../core/common';
import {ProductService} from '../../product/product.service';
import {Product_categoryService} from '../../product_category/product_category.service';
import {PostService} from '../../post/post.service';
import {PostCategoryService} from '../../post-category/post-category.service';
import {PageService} from '../../page/page.service';

@Component({
  selector: 'app-menu-edit',
  templateUrl: './menu-edit.component.html',
  styleUrls: ['./menu-edit.component.css'],
  providers: [MenuService, ProductService, Product_categoryService, PostService, PostCategoryService, PageService]
})
export class MenuEditComponent extends AbstractModalComponent<MenuMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  buildForm(): FormGroup {
    return this.formBuilder.group({
      name: new FormControl(null, Validators.required),
      menuable: new FormControl(null),
      menuable_type: new FormControl(null),
      menuable_id: new FormControl(null),
      url: new FormControl(null, Validators.required),
      parent: new FormControl(null),
      parent_id: new FormControl(null),
      menu_position_id: new FormControl(null),
    });
  }


  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect2('Menu cha', 'parent', 'Chọn menu cha', 'loadMenu'),
      FieldForm.createSelect('Loại cấu hình', 'menuable_type', 'Chọn một loại cấu hình', [
        {
          value: 'manual',
          name: 'Liên kết'
        },
        {
          value: 'pages',
          name: 'Trang',
        },
        {
          value: 'post_categories',
          name: 'Thể loại bài viết',
        },
        {
          value: 'product_categories',
          name: 'Thể loại sản phẩm',
        },
        {
          value: 'posts',
          name: 'Bài viết',
        },
        {
          value: 'products',
          name: 'Sản phẩm',
        }
      ], null, 'name', 'value'),
      FieldForm.createSingleSelect2('Dữ liệu', 'menuable', 'Chọn một', 'loadPage'),
      FieldForm.createTextInput('Tên hiển thị *', 'name', 'Nhập tên'),
      FieldForm.createTextInput('Đường dẫn url *', 'url', 'Nhập url'),
    ];
  }

  loadMenu() {
    return this.service.loadAll();
  }

  loadPage() {
    return this.pageService.loadAll().map((value, index) => {
      return value.map(v => {
        return {id: v.id, name: v.name, url: v.full_path};
      });
    });
  }

  loadPostCategory() {
    return this.postCategoryService.loadAll().map((value, index) => {
      return value.map(v => {
        return {id: v.id, name: v.name, url: v.full_path};
      });
    });
  }

  loadProductCategory() {
    return this.productCategoryService.loadAll().map((value, index) => {
      return value.map(v => {
        return {id: v.id, name: v.name, url: v.full_path};
      });
    });
  }

  loadPost() {
    return this.postService.loadAll().map((value, index) => {
      return value.map(v => {
        return {id: v.id, name: v.name, url: v.full_path};
      });
    });
  }

  loadProduct() {
    return this.productService.loadAll().map((value, index) => {
      return value.map(v => {
        return {id: v.id, name: v.name, url: v.full_path};
      });
    });
  }

  loaded(): void {
  }

  constructor(
    service: MenuService,
    modal: BsModalRef,
    builder: FormBuilder,
    private menuService: MenuService,
    private productService: ProductService,
    private productCategoryService: Product_categoryService,
    private postService: PostService,
    private postCategoryService: PostCategoryService,
    private pageService: PageService
  ) {
    super(service, modal, builder);
  }

  onFormChanged() {
    this.formGroup.controls['parent'].valueChanges.subscribe((value => {
      if (value && value.length > 0) {
        this.formGroup.controls['parent_id'].setValue(value[0].id);
      } else {
        this.formGroup.controls['parent_id'].setValue(0);
      }
    }));
    this.formGroup.controls['menuable_type'].valueChanges.subscribe((value => {
      this.formGroup.controls['menuable_id'].setValue(null);
      this.formGroup.controls['menuable'].setValue(null);
      if (!value) {
        return;
      }
      let fieldSelected = this.fields[2];
      if (value === 'pages') {
        this.loadPage().subscribe(value => {
          fieldSelected.data = value;
        });
      }
      if (value === 'post_categories') {
        this.loadPostCategory().subscribe(value => {
          fieldSelected.data = value;
        });
      }
      if (value === 'product_categories') {
        this.loadProductCategory().subscribe(value => {
          fieldSelected.data = value;
        });
      }
      if (value === 'posts') {
        this.loadPost().subscribe(value => {
          fieldSelected.data = value;
        });
      }
      if (value === 'products') {
        this.loadProduct().subscribe(value => {
          fieldSelected.data = value;
        });
      }
      if (value === 'manual') {
        fieldSelected.data = [];
      }
    }));

    this.formGroup.controls['menuable'].valueChanges.subscribe(value => {
      if (value && value.length > 0) {
        let element = value[0];
        this.formGroup.controls['menuable_id'].setValue(element['id']);
        this.formGroup.controls['name'].setValue(element['name']);
        this.formGroup.controls['url'].setValue(element['url']);
      } else {
        this.formGroup.controls['menuable_id'].setValue(null);
        this.formGroup.controls['name'].setValue(null);
        this.formGroup.controls['url'].setValue(null);
      }
    });
  }
}


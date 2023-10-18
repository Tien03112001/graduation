import { Component } from '@angular/core';
import { AbstractModalComponent } from '../../../core/crud';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { BsModalRef } from 'ngx-bootstrap';
import { FieldForm } from '../../../core/common';
import { FacebookProductService } from '../facebook-product.service';
import { FacebookProductMeta } from '../facebook-product.meta';
import { FacebookProductCategoryService } from '../../facebook-product-category/facebook-product-category.service';
import { FacebookProductCategoryMeta } from '../../facebook-product-category/facebook-product-category.meta';
import { ObjectUtil } from '../../../core/utils';

@Component({
  selector: 'app-facebook-product-edit',
  templateUrl: './facebook-product-edit.component.html',
  styleUrls: ['./facebook-product-edit.component.css'],
  providers: [FacebookProductService, FacebookProductCategoryService]
})
export class FacebookProductEditComponent extends AbstractModalComponent<FacebookProductMeta> {

  onInit(): void {
  }

  onDestroy(): void {
  }

  loadAllCategories() {
    return this.categoryService.loadAll();
  }


  buildForm(): FormGroup {
    return this.formBuilder.group({
      facebook_product_category: new FormControl(null, Validators.required),
      fb_product_category: new FormControl(null),
      gender_list: new FormControl(null),
      age_group_list: new FormControl(null),
      material: new FormControl(null),
      height: new FormControl(null),
      length: new FormControl(null),
      width: new FormControl(null),
      pattern: new FormControl(null),
      color: new FormControl(null),
      gender: new FormControl(null),
      age_group: new FormControl(null),
      finish: new FormControl(null),
      volume: new FormControl(null),
      scent: new FormControl(null),
      decor_style: new FormControl(null),
      gemstone: new FormControl(null),
      ingredients: new FormControl(null),
    });
  }

  initFieldForm(): FieldForm[] {
    return [
      FieldForm.createSingleSelect2('Category *', 'facebook_product_category', 'Chọn một', 'loadAllCategories'),
      FieldForm.createTextInput('Color', 'color', 'Red, black'),
      FieldForm.createSingleSelect2('Gender', 'gender_list', 'Chọn một', [
        {
          id: 0,
          value: 'female',
          name: 'Female',
        },
        {
          id: 1,
          value: 'male',
          name: 'Male',
        },
        {
          id: 2,
          value: 'unisex',
          name: 'Unisex',
        }
      ]),
      FieldForm.createSingleSelect2('Age group', 'age_group_list', 'Chọn một', [
        {
          id: 0,
          value: 'adult',
          name: 'Adult',
        },{
          id: 1,
          value: 'all age',
          name: 'All age',
        },
        {
          id: 2,
          value: 'teen',
          name: 'Teen',
        },
        {
          id: 3,
          value: 'kids',
          name: 'Kids',
        },
        {
          id: 4,
          value: 'toddler',
          name: 'Toddler',
        },
        {
          id: 5,
          value: 'infant',
          name: 'Infant',
        },
        {
          id: 6,
          value: 'newborn',
          name: 'Newborn',
        }
      ]),
    ];
  }

  loaded(): void {
    if (this.model.category != null && this.model.category.additional_field != null) {
      let selectData = this.model.category.additional_field.split(',').map(v => {
        return { id: v, name: v };
      });
      for (let i of selectData) {
        if(i.name == 'material') {
          let fileForm = FieldForm.createTextInput('Material', 'material', 'Cotton, linen');
          this.fields.push(fileForm);
        }
        if(i.name == 'pattern') {
          let fileForm = FieldForm.createTextInput('Pattern', 'pattern', 'Plaid, polka dot');
          this.fields.push(fileForm);
        }
        if(i.name == 'height') {
          let fileForm = FieldForm.createTextInput('Height', 'height', '5 in, 2 m');
          this.fields.push(fileForm);
        }
        if(i.name == 'length') {
          let fileForm = FieldForm.createTextInput('Length', 'length', '5 in, 2 m');
          this.fields.push(fileForm);
        }
        if(i.name == 'width') {
          let fileForm = FieldForm.createTextInput('Width', 'width', '5 in, 2 m');
          this.fields.push(fileForm);
        }
        if(i.name == 'finish') {
          let fileForm = FieldForm.createTextInput('Finish', 'finish', 'Walnut, pewter');
          this.fields.push(fileForm);
        }
        if(i.name == 'volume') {
          let fileForm = FieldForm.createTextInput('Volume', 'volume', '12 oz, 1 litre');
          this.fields.push(fileForm);
        }
        if(i.name == 'scent') {
          let fileForm = FieldForm.createTextInput('Scent', 'scent', 'Lavender, vanilla');
          this.fields.push(fileForm);
        }
        if(i.name == 'decor_style') {
          let fileForm = FieldForm.createTextInput('Decor style', 'decor_style', 'Bohemian, contemporary');
          this.fields.push(fileForm);
        }
        if(i.name == 'gemstone') {
          let fileForm = FieldForm.createTextInput('Gemstone', 'gemstone', 'Diamond, ruby');
          this.fields.push(fileForm);
        }
        if(i.name == 'ingredients') {
          let fileForm = FieldForm.createTextInput('Ingredients', 'ingredients', 'Vitamin C, axit alpha hydroxy');
          this.fields.push(fileForm);
        }
      }
    }
  }

  constructor(
    service: FacebookProductService,
    modal: BsModalRef,
    builder: FormBuilder,
    public categoryService: FacebookProductCategoryService
  ) {
    super(service, modal, builder);
  }

  public setFormValue() {
    let item: any = ObjectUtil.clone(this.model);
    item.category = [];
    item.gender = [];
    item.age_group = [];
    let categoryObj = this.fields[0].data.filter(v => v.id == this.model.fb_product_category);
    let genderObj = this.fields[2].data.filter(v => v.value == this.model.gender);
    let age_groupObj = this.fields[3].data.filter(v => v.value == this.model.age_group);
    item.facebook_product_category = categoryObj;
    item.gender_list = genderObj;
    item.age_group_list = age_groupObj;
    this.formGroup.patchValue(ObjectUtil.mergeValue(this.formGroup.value, item));
  }

  onFormChanged() {
    super.onFormChanged();
    this.formGroup.controls['facebook_product_category'].valueChanges.subscribe(value => {
      this.categoryService.loadByParams({ search: value }).subscribe((category: FacebookProductCategoryMeta[]) => {
        if(this.fields.length > 4) {
          this.fields.splice(4);
        }
        if (value != null && value.length > 0 && value[0] != null ) {
          this.formGroup.controls['fb_product_category'].setValue(value[0].id);
        }
        if (value != null && value.length > 0 && value[0].additional_field != null) {
          let selectData = value[0].additional_field.split(',').map(v => {
            return {name: v};
          });
          for (let i of selectData) {
            if(i.name == 'material') {
              let fileForm = FieldForm.createTextInput('Material', 'material', 'Cotton, linen');
              this.fields.push(fileForm);
            }
            if(i.name == 'height') {
              let fileForm = FieldForm.createTextInput('Height', 'height', '5 in, 2 m');
              this.fields.push(fileForm);
            }
            if(i.name == 'length') {
              let fileForm = FieldForm.createTextInput('Length', 'length', '5 in, 2 m');
              this.fields.push(fileForm);
            }
            if(i.name == 'width') {
              let fileForm = FieldForm.createTextInput('Width', 'width', '5 in, 2 m');
              this.fields.push(fileForm);
            }
            if(i.name == 'pattern') {
              let fileForm = FieldForm.createTextInput('Pattern', 'pattern', 'Plaid, polka dot');
              this.fields.push(fileForm);
            }
            if(i.name == 'finish') {
              let fileForm = FieldForm.createTextInput('Finish', 'finish', 'Walnut, pewter');
              this.fields.push(fileForm);
            }
            if(i.name == 'volume') {
              let fileForm = FieldForm.createTextInput('Volume', 'volume', '12 oz, 1 litre');
              this.fields.push(fileForm);
            }
            if(i.name == 'scent') {
              let fileForm = FieldForm.createTextInput('Scent', 'scent', 'Lavender, vanilla');
              this.fields.push(fileForm);
            }
            if(i.name == 'decor_style') {
              let fileForm = FieldForm.createTextInput('Decor style', 'decor_style', 'Bohemian, contemporary');
              this.fields.push(fileForm);
            }
            if(i.name == 'gemstone') {
              let fileForm = FieldForm.createTextInput('Gemstone', 'gemstone', 'Diamond, ruby');
              this.fields.push(fileForm);
            }
            if(i.name == 'ingredients') {
              let fileForm = FieldForm.createTextInput('Ingredients', 'ingredients', 'Vitamin C, axit alpha hydroxy');
              this.fields.push(fileForm);
            }
          }
        }
      });
    })
    this.formGroup.controls['gender_list'].valueChanges.subscribe((value: any) => {
      if (value && value.length > 0) {
        this.formGroup.controls['gender'].setValue(value[0].value);
      }
    });
    this.formGroup.controls['age_group_list'].valueChanges.subscribe((value: any) => {
      if (value && value.length > 0) {
        this.formGroup.controls['age_group'].setValue(value[0].value);
      }
    });
  }

}

import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PromotionProductCreateComponent} from './promotion-product-create/promotion-product-create.component';
import {PromotionProductEditComponent} from './promotion-product-edit/promotion-product-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {ArticleModule} from '../article/article.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ArticleCommentModule} from '../article-comment/article-comment.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {StructuredDataModule} from '../structured-data/structured-data.module';
import {PromotionProductListComponent} from './promotion-product-list/promotion-product-list.component';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    AngularMultiSelectModule,
    MetaDataModule,
    ArticleModule,
    ArticleCommentModule,
    StructuredDataModule,
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    CollapseModule,
  ],
  declarations: [
    PromotionProductCreateComponent,
    PromotionProductEditComponent,
    PromotionProductListComponent
  ],
  entryComponents: [
    PromotionProductCreateComponent,
    PromotionProductEditComponent,
    PromotionProductListComponent,
  ],
  exports: [
    PromotionProductCreateComponent,
    PromotionProductEditComponent,
    PromotionProductListComponent
  ]
})
export class PromotionProductModalModule {
}

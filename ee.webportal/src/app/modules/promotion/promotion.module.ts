import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PromotionListComponent} from './promotion-list/promotion-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {ArticleModule} from '../article/article.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ArticleCommentModule} from '../article-comment/article-comment.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {StructuredDataModule} from '../structured-data/structured-data.module';
import {PromotionModalModule} from './promotion.modal.module';
import {BlockModalModule} from '../block/block.modal.module';
import {PromotionEditComponent} from './promotion-edit/promotion-edit.component';
import {PromotionCreateComponent} from './promotion-create/promotion-create.component';
import {PromotionProductModalModule} from '../promotion-product/promotion-product.modal.module';

const routing: Routes = [
  {
    path: '',
    component: PromotionListComponent
  },
];

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    RouterModule.forChild(routing),
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
    PromotionModalModule,
    BlockModalModule,
    PromotionProductModalModule,
  ],
  declarations: [
  ],
  entryComponents: [ ],
  exports: []
})
export class PromotionModule {
}

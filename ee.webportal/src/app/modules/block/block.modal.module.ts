import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {BlockCreateComponent} from './block-create/block-create.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {ArticleModule} from '../article/article.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ArticleCommentModule} from '../article-comment/article-comment.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {StructuredDataModule} from '../structured-data/structured-data.module';
import {BlockViewComponent} from './block-view/block-view.component';
import {BlockListComponent} from './block-list/block-list.component';

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
    TabsModule,
  ],
  declarations: [
    BlockListComponent,
    BlockCreateComponent,
    BlockViewComponent,
  ],
  entryComponents: [
    BlockListComponent,
    BlockCreateComponent,
    BlockViewComponent,
  ],
  exports: [
    BlockListComponent,
    BlockCreateComponent,
    BlockViewComponent,
  ]
})
export class BlockModalModule {
}

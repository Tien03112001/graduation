import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PostListComponent} from './post-list/post-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {CKEditorModule} from 'ng2-ckeditor';
import {ArticleModule} from '../article/article.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ArticleCommentModule} from '../article-comment/article-comment.module';
import {StructuredDataModule} from '../structured-data/structured-data.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {GalleryCRUDModule} from '../gallery-CRUD/gallery-crud.module';
import {PostModalModule} from './post.modal.module';
import {PostTagModalModule} from '../post-tag/post-tag.modal.module';

const routing: Routes = [
  {
    path: '',
    component: PostListComponent
  }
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
    MetaDataModule,
    PhotoCrudModule,
    ArticleModule,
    ArticleCommentModule,
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    StructuredDataModule,
    GalleryCRUDModule,
    TabsModule.forRoot(),
    AngularMultiSelectModule,
    PostModalModule,
    PostTagModalModule
  ],
  declarations: [PostListComponent],
  entryComponents: [],
  exports: []
})
export class PostModule {
}

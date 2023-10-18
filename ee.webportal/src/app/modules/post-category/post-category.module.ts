import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PostCategoryListComponent} from './post-category-list/post-category-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {PostCategoryModalModule} from './post-category.modal.module';

const routing: Routes = [
  {
    path: '',
    component: PostCategoryListComponent
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
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    AngularMultiSelectModule,
    TabsModule.forRoot(),
    PostCategoryModalModule
  ],
  declarations: [PostCategoryListComponent],
  entryComponents:[],
})
export class PostCategoryModule {
}

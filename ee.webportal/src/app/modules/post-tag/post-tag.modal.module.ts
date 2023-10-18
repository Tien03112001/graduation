import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PostTagCreateComponent} from './post-tag-create/post-tag-create.component';
import {PostTagEditComponent} from './post-tag-edit/post-tag-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {PostTagAssignComponent} from './post-tag-assign/post-tag-assign.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    NgSelectModule,
    MetaDataModule,
    PhotoCrudModule,
    AngularMultiSelectModule,
    CKEditorModule,
  ],
  declarations: [
    PostTagCreateComponent,
    PostTagEditComponent,
    PostTagAssignComponent
  ],
  entryComponents: [
    PostTagCreateComponent,
    PostTagEditComponent,
    PostTagAssignComponent
  ],
  exports: [
    PostTagCreateComponent,
    PostTagEditComponent,
    PostTagAssignComponent
  ]
})
export class PostTagModalModule {
}

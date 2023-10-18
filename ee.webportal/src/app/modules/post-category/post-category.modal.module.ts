import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {PostCategoryCreateComponent} from './post-category-create/post-category-create.component';
import {PostCategoryEditComponent} from './post-category-edit/post-category-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    MetaDataModule,
    PhotoCrudModule,
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    AngularMultiSelectModule,
    TabsModule.forRoot(),
  ],
  declarations: [ PostCategoryCreateComponent, PostCategoryEditComponent],
  entryComponents: [PostCategoryEditComponent, PostCategoryCreateComponent],
  exports: [PostCategoryEditComponent, PostCategoryCreateComponent],
})
export class PostCategoryModalModule {
}

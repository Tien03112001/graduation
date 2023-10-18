import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {GalleryImageComponent} from './gallery-image/gallery-image.component';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {GalleryImageModalModule} from './gallery-image.modal.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    PaginationModule.forRoot(),
    PopoverModule.forRoot(),
    ModalModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    UiSwitchModule,
    PhotoCrudModule,
    AngularMultiSelectModule,
    CKEditorModule,
    GalleryImageModalModule
  ],
  declarations: [ GalleryImageComponent],
  entryComponents: [ GalleryImageComponent],
  exports: [ GalleryImageComponent]
})
export class GalleryCRUDModule {
}

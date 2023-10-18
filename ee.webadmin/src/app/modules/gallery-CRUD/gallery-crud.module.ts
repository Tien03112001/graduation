import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {GalleryImageComponent} from './gallery-image/gallery-image.component';
import {GalleryCreateComponent} from './gallery-create/gallery-create.component';
import {GalleryEditComponent} from './gallery-edit/gallery-edit.component';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';

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
    CKEditorModule
  ],
  declarations: [GalleryCreateComponent, GalleryEditComponent, GalleryImageComponent],
  entryComponents: [GalleryCreateComponent, GalleryEditComponent, GalleryImageComponent],
  exports: [GalleryCreateComponent, GalleryEditComponent, GalleryImageComponent]
})
export class GalleryCRUDModule {
}

import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {CacheCreateComponent} from './cache-create/cache-create.component';
import {CacheEditComponent} from './cache-edit/cache-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {NgSelectModule} from '@ng-select/ng-select';
import {BannerModule} from '../banner/banner.module';
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
    BannerModule,
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [CacheCreateComponent, CacheEditComponent],
  entryComponents: [CacheCreateComponent, CacheEditComponent],
  exports: [CacheCreateComponent, CacheEditComponent],
})
export class CacheModalModule {
}

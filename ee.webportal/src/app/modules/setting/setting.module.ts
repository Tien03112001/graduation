import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {SettingListComponent} from './setting-list/setting-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {SettingCreateComponent} from './setting-create/setting-create.component';
import {SettingEditComponent} from './setting-edit/setting-edit.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';

const routing: Routes = [
  {
    path: '',
    component: SettingListComponent
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
    AngularMultiSelectModule,
    CKEditorModule,
  ],
  declarations: [SettingListComponent, SettingCreateComponent, SettingEditComponent],
  entryComponents: [SettingCreateComponent, SettingEditComponent],
  exports: []
})
export class SettingModule {
}

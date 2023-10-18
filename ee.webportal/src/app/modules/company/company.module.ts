import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {CompanyListComponent} from './company-list/company-list.component';
import {CompanyCreateComponent} from './company-create/company-create.component';
import {CompanyEditComponent} from './company-edit/company-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';

const routing: Routes = [
  {
    path: '',
    component: CompanyListComponent
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
    NgSelectModule,
    CKEditorModule,
    AngularMultiSelectModule,
  ],
  declarations: [CompanyListComponent, CompanyCreateComponent, CompanyEditComponent],
  entryComponents: [CompanyCreateComponent, CompanyEditComponent]
})
export class CompanyModule {
}

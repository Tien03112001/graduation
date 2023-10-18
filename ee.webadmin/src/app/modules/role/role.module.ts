import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {RoleListComponent} from './role-list/role-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {RoleModalModule} from './role.modal.module';

const routing: Routes = [
  {
    path: '',
    component: RoleListComponent
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(routing),
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    AngularMultiSelectModule,
    RoleModalModule
  ],
  declarations: [
    RoleListComponent
  ],
  entryComponents: [],
  exports: []
})
export class RoleModule {
}

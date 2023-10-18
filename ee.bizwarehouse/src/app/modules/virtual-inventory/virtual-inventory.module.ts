import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {VirtualInventoryListComponent} from './virtual-inventory-list/virtual-inventory-list.component';

const routing: Routes = [
  {
    path: '',
    component: VirtualInventoryListComponent
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
    MomentModule
  ],
  declarations: [
    VirtualInventoryListComponent,
  ],
  entryComponents: [],
  exports: []
})
export class VirtualInventoryModule {
}

import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {ChanelListComponent} from './chanel-list/chanel-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {ChanelModalModule} from './chanel.modal.module';

const routing: Routes = [
  {
    path: '',
    component: ChanelListComponent
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
    ChanelModalModule
  ],
  declarations: [
    ChanelListComponent
  ],
  entryComponents: [],
  exports: []
})
export class ChanelModule {
}

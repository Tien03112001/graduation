import {NgModule} from '@angular/core';
import {CollapseModule, ModalModule, PaginationModule, PopoverModule, TypeaheadModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {OrderListComponent} from './order-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import { OrderCrudModule } from './order.crud.module';

const routing: Routes = [
  {
    path: '',
    component: OrderListComponent
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
    MomentModule,
    CollapseModule,
    TypeaheadModule.forRoot(),
    OrderCrudModule
  ],
  declarations: [
    OrderListComponent,
  ],
  entryComponents: [
  ],
  exports: []
})
export class OrderModule {
}

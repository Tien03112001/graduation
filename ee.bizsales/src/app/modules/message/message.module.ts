import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {MessageListComponent} from './message-list/message-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MessageModalModule} from './message.modal.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    AngularMultiSelectModule,
    MessageModalModule
  ],
  declarations: [
    MessageListComponent
  ],
  entryComponents: [],
  exports: [
    MessageListComponent
  ]
})
export class MessageModule {
}

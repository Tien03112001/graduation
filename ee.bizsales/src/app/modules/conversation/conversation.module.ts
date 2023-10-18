import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {ConversationListComponent} from './conversation-list/conversation-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {ConversationModalModule} from './conversation.modal.module';
import {MessageModule} from '../message/message.module';

const routing: Routes = [
  {
    path: '',
    component: ConversationListComponent
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
    ConversationModalModule,
    MessageModule
  ],
  declarations: [
    ConversationListComponent,
  ],
  entryComponents: [

  ],
  exports: []
})
export class ConversationModule {
}

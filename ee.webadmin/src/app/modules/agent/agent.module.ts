import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {AgentListComponent} from './agent-list/agent-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {AgentModalModule} from './agent.modal.module';

const routing: Routes = [
  {
    path: '',
    component: AgentListComponent
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
    AgentModalModule
  ],
  declarations: [
    AgentListComponent
  ],
  entryComponents: [],
  exports: []
})
export class AgentModule {
}

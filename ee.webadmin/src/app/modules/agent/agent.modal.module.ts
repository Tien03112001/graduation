import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AgentCreateComponent} from './agent-create/agent-create.component';
import {AgentEditComponent} from './agent-edit/agent-edit.component';
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
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [
    AgentCreateComponent, AgentEditComponent
  ],
  entryComponents: [
    AgentCreateComponent, AgentEditComponent
  ],
  exports: [
    AgentCreateComponent, AgentEditComponent
  ]
})
export class AgentModalModule {
}

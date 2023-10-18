import { NgModule } from '@angular/core';
import { ModalModule, PaginationModule, PopoverModule } from 'ngx-bootstrap';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ConfirmationPopoverModule } from 'angular-confirmation-popover';
import { UiSwitchModule } from 'ngx-toggle-switch';
import { AngularMultiSelectModule } from 'angular2-multiselect-dropdown';
import { CKEditorModule } from 'ng2-ckeditor';
import { AgentPageCreateComponent } from './agent-page-create/agent-page-create.component';
import { AgentPageEditComponent } from './agent-page-edit/agent-page-edit.component';
import { AgentPageListComponent } from './agent-page-list/agent-page-list.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    UiSwitchModule,
    AngularMultiSelectModule,
    CKEditorModule
  ],
  declarations: [
    AgentPageListComponent, AgentPageCreateComponent, AgentPageEditComponent
  ],
  entryComponents: [
    AgentPageListComponent, AgentPageCreateComponent, AgentPageEditComponent
  ],
  exports: [
    AgentPageListComponent, AgentPageCreateComponent, AgentPageEditComponent
  ]
})
export class AgentPageModalModule {
}

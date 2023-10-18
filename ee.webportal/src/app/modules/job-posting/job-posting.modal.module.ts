import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {CKEditorModule} from 'ng2-ckeditor';
import {JobPostingCreateComponent} from './job-posting-create/job-posting-create.component';
import {JobPostingEditComponent} from './job-posting-edit/job-posting-edit.component';
import {NgSelectModule} from '@ng-select/ng-select';
import {UiSwitchModule} from 'ngx-toggle-switch';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    PaginationModule.forRoot(),
    ReactiveFormsModule,
    PopoverModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    ModalModule.forRoot(),
    NgSelectModule,
    CKEditorModule,
    UiSwitchModule,
    TabsModule.forRoot(),
    AngularMultiSelectModule,
  ],
  declarations: [
    JobPostingCreateComponent, JobPostingEditComponent
  ],
  entryComponents: [
    JobPostingCreateComponent, JobPostingEditComponent
  ],
  exports: [
    JobPostingCreateComponent, JobPostingEditComponent
  ]
})
export class JobPostingModalModule {
}

import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {JobPostingCvListComponent} from './job-posting-cv-list/job-posting-cv-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';

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
        AngularMultiSelectModule,
    ],
  declarations: [JobPostingCvListComponent],
  entryComponents: [JobPostingCvListComponent],
  exports: [JobPostingCvListComponent]
})
export class JobPostingCvModule {
}

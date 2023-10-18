import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {JobPostingListComponent} from './job-posting-list/job-posting-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {NgSelectModule} from '@ng-select/ng-select';
import {CKEditorModule} from 'ng2-ckeditor';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {JobPostingModalModule} from './job-posting.modal.module';
import {MomentModule} from 'ngx-moment';
import {JobPostingCvModule} from '../job-posting-cv/job-posting-cv.module';

const routing: Routes = [
  {
    path: '',
    component: JobPostingListComponent
  }
];

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        PaginationModule.forRoot(),
        ReactiveFormsModule,
        PopoverModule.forRoot(),
        ConfirmationPopoverModule.forRoot(),
        RouterModule.forChild(routing),
        ModalModule.forRoot(),
        NgSelectModule,
        CKEditorModule,
        UiSwitchModule,
        TabsModule.forRoot(),
        AngularMultiSelectModule,
        JobPostingModalModule,
        MomentModule,
        JobPostingCvModule,
    ],
  declarations: [JobPostingListComponent],
  entryComponents: [],
  exports: []
})
export class JobPostingModule {
}

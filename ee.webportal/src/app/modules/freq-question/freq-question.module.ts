import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {FreqQuestionListComponent} from './freq-question-list/freq-question-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {FreqQuestionModalModule} from './freq-question.modal.module';

const routing: Routes = [
  {
    path: '',
    component: FreqQuestionListComponent
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
    FreqQuestionModalModule
  ],
  declarations: [
    FreqQuestionListComponent
  ],
  entryComponents: [],
  exports: []
})
export class FreqQuestionModule {
}

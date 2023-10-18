import {NgModule} from '@angular/core';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {CommonModule} from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {ExportingNoteListComponent} from './exporting-note-list.component';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {MomentModule} from 'ngx-moment';
import {ExportingNoteCreateComponent} from './exporting-note-create/exporting-note-create.component';

const routing: Routes = [
  {
    path: '',
    component: ExportingNoteListComponent
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
    MomentModule
  ],
  declarations: [
    ExportingNoteListComponent,
    ExportingNoteCreateComponent
  ],
  entryComponents: [
    ExportingNoteCreateComponent
  ],
  exports: []
})
export class ExportingNoteModule {
}

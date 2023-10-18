import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {EmbedCodeListComponent} from './embed-code-list/embed-code-list.component';
import {EmbedCodeCreateComponent} from './embed-code-create/embed-code-create.component';
import {EmbedCodeEditComponent} from './embed-code-edit/embed-code-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';

const routing: Routes = [
  {
    path: '',
    component: EmbedCodeListComponent
  }
];

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    PaginationModule.forRoot(),
    PopoverModule.forRoot(),
    ModalModule.forRoot(),
    ConfirmationPopoverModule.forRoot(),
    RouterModule.forChild(routing),
  ],
  declarations: [EmbedCodeListComponent, EmbedCodeCreateComponent, EmbedCodeEditComponent],
  entryComponents: [EmbedCodeCreateComponent, EmbedCodeEditComponent]
})
export class EmbedCodeModule {
}

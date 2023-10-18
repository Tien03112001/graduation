import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {LanguageListComponent} from './language-list/language-list.component';
import {LanguageCreateComponent} from './language-create/language-create.component';
import {LanguageEditComponent} from './language-edit/language-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';

const routing: Routes = [
  {
    path: '',
    component: LanguageListComponent
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
  declarations: [LanguageListComponent, LanguageCreateComponent, LanguageEditComponent],
  entryComponents: [LanguageCreateComponent, LanguageEditComponent]
})
export class LanguageModule {
}

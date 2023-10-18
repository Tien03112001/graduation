import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {WidgetListComponent} from './widget-list/widget-list.component';
import {WidgetCreateComponent} from './widget-create/widget-create.component';
import {WidgetEditComponent} from './widget-edit/widget-edit.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {SafeHtmlPipe} from './saveHtmlPipe';
import {CKEditorModule} from 'ng2-ckeditor';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';

const routing: Routes = [
  {
    path: '',
    component: WidgetListComponent
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
    TabsModule.forRoot(),
    CKEditorModule,
    AngularMultiSelectModule,
  ],
  declarations: [WidgetListComponent, WidgetCreateComponent, WidgetEditComponent, SafeHtmlPipe],
  exports: [
    SafeHtmlPipe
  ],
  entryComponents: [WidgetCreateComponent, WidgetEditComponent]
})
export class WidgetModule {
}

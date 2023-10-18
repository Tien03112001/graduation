import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {RelatedPostListComponent} from './related-post-list/related-post-list.component';
import {RelatedPostCreateComponent} from './related-post-create/related-post-create.component';
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
  declarations: [RelatedPostListComponent, RelatedPostCreateComponent],
  entryComponents: [RelatedPostListComponent, RelatedPostCreateComponent],
  exports: [RelatedPostListComponent, RelatedPostCreateComponent]
})
export class RelatedPostModule {
}

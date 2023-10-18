import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {RelatedProductListComponent} from './related-product-list/related-product-list.component';
import {RelatedProductCreateComponent} from './related-product-create/related-product-create.component';
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
  declarations: [RelatedProductListComponent, RelatedProductCreateComponent],
  entryComponents: [RelatedProductListComponent, RelatedProductCreateComponent],
  exports: [RelatedProductListComponent, RelatedProductCreateComponent]
})
export class RelatedProductModule {
}

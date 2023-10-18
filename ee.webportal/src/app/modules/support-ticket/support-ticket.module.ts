import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {SupportTicketListComponent} from './support-ticket-list/support-ticket-list.component';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalModule, PaginationModule, PopoverModule, TabsModule} from 'ngx-bootstrap';
import {ConfirmationPopoverModule} from 'angular-confirmation-popover';
import {RouterModule, Routes} from '@angular/router';
import {MetaDataModule} from '../meta-data/meta-data.module';
import {NgSelectModule} from '@ng-select/ng-select';
import {PhotoCrudModule} from '../photo-CRUD/photo-crud.module';
import {CKEditorModule} from 'ng2-ckeditor';
import {ArticleModule} from '../article/article.module';
import {UiSwitchModule} from 'ngx-toggle-switch';
import {ArticleCommentModule} from '../article-comment/article-comment.module';
import {StructuredDataModule} from '../structured-data/structured-data.module';
import {AngularMultiSelectModule} from 'angular2-multiselect-dropdown';
import {GalleryCRUDModule} from '../gallery-CRUD/gallery-crud.module';
import {SupportTicketModalModule} from './support-ticket.modal.module';
import {SupportDetailComponent} from './support-detail/support-detail.component';
import {MomentModule} from 'ngx-moment';

const routing: Routes = [
  {
    path: '',
    component: SupportTicketListComponent
  },
  {
    path: 'support-details/:id',
    component: SupportDetailComponent
  },
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
        MetaDataModule,
        PhotoCrudModule,
        ArticleModule,
        ArticleCommentModule,
        NgSelectModule,
        CKEditorModule,
        UiSwitchModule,
        StructuredDataModule,
        GalleryCRUDModule,
        TabsModule.forRoot(),
        AngularMultiSelectModule,
        SupportTicketModalModule,
        MomentModule,

    ],
  declarations: [SupportTicketListComponent, SupportDetailComponent],
  entryComponents: [],
  exports: []
})
export class SupportTicketModule {
}

import {Component} from '@angular/core';
import {AbstractCRUDComponent,} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {TitleService} from '../../../core/services';
import {SupportTicketMeta} from '../support-ticket.meta';
import {SupportTicketService} from '../support-ticket.service';
import {SupportTicketEditComponent} from '../support-ticket-edit/support-ticket-edit.component';
import {MetaDataService} from '../../meta-data/meta-data.service';
import {SupportTicketCreateComponent} from '../support-ticket-create/support-ticket-create.component';

@Component({
  selector: 'app-support-ticket',
  templateUrl: './support-ticket-list.component.html',
  styleUrls: ['./support-ticket-list.component.css'],
  providers: [SupportTicketService, MetaDataService]
})
export class SupportTicketListComponent extends AbstractCRUDComponent<SupportTicketMeta> {
  slected: any;

  onInit(): void {
    this.load();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Hỗ trợ';
  }

  getCreateModalComponent(): any {
    return SupportTicketCreateComponent;
  }

  getEditModalComponent(): any {
    return SupportTicketEditComponent;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }

  getEditModalComponentOptions(): ModalOptions {
    return {'class': 'modal-lg', ignoreBackdropClick: true};
  }


  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
      status: new FormControl(''),
    });
  }

  initNewModel(): SupportTicketMeta {
    return new SupportTicketMeta();
  }

  constructor(
    service: SupportTicketService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
  ) {
    super(service, modal, builder);
  }

  RowSelected(item: any) {
    this.slected = item.id;
  }
}

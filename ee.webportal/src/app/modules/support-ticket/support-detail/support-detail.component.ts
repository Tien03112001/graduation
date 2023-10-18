import {Component} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {SupportTicketService} from '../support-ticket.service';
import {SupportTicketMeta} from '../support-ticket.meta';
import {AbstractCRUDComponent} from '../../../core/crud';
import {BsModalService, ModalOptions} from 'ngx-bootstrap';
import {TitleService} from '../../../core/services';
import {FormBuilder, FormControl, FormGroup} from '@angular/forms';
import {SupportDetailEditComponent} from '../support-detail-edit/support-detail-edit.component';

const ONE_DAY = 86000000;

@Component({
  selector: 'app-support-ticket-detail',
  templateUrl: './support-detail.component.html',
  styleUrls: ['./support-detail.component.css'],
  providers: [SupportTicketService]
})
export class SupportDetailComponent extends AbstractCRUDComponent<SupportTicketMeta> {
  list: any;
  currentTime: any = new Date();

  onInit() {
    this.getSupportDetail();
  }

  onDestroy(): void {
  }

  getTitle(): string {
    return 'Hỗ trợ';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return SupportDetailEditComponent;
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

  constructor(
    service: SupportTicketService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private supportService: SupportTicketService,
    private route: ActivatedRoute
  ) {
    super(service, modal, builder);
  }

  initNewModel(): SupportTicketMeta {
    return new SupportTicketMeta();
  }

  getSupportDetail() {
    this.route.params.subscribe(param => {
      this.supportService.getSupportDetails(param.id).subscribe(data => {
        this.list = data;
      });
    });
  }

  checkTimeOneDay(ended_at){
    const checkOutTime: Date = new Date(ended_at);
    if (checkOutTime.getTime() - this.currentTime.getTime() <= ONE_DAY){
      return true;
    }else {
      return false;
    }
  }
  checkTimeTowDay(ended_at){
    const checkOutTime: Date = new Date(ended_at);
    if (checkOutTime.getTime() - this.currentTime.getTime() <= ONE_DAY*2 && checkOutTime.getTime() - this.currentTime.getTime() > ONE_DAY){
      return true;
    }else {
      return false;
    }
  }
  checkTimeThreeDay(ended_at){
    if (!this.checkTimeOneDay(ended_at) && !this.checkTimeTowDay(ended_at)){
      return true;
    }else {
      return false;
    }
  }




}

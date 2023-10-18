import { Component, ViewChild } from '@angular/core';
import { BsModalService, ModalOptions } from 'ngx-bootstrap';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { AbstractCRUDComponent, FieldForm, StorageUtil, TitleService } from '../../../core';
import { ConversationMeta } from '../conversation.meta';
import { ConversationService } from '../conversation.service';
import { WebSocketService } from '../../../services/web-socket.service';
import { AgentService } from '../../agent/agent.service';

@Component({
  selector: 'app-conversation-list',
  templateUrl: './conversation-list.component.html',
  styleUrls: ['./conversation-list.component.css'],
  providers: [ConversationService, WebSocketService, AgentService]
})
export class ConversationListComponent extends AbstractCRUDComponent<ConversationMeta> {

  conversationSelected: any;
  newMessage: any;
  conversationChanged: any;
  status: number = 0;
  listNew: number = 0;

  onInit(): void {
    this.agentService.check(StorageUtil.get('token')).subscribe(res => {
      this.service.toastSuccess('Truy cập hệ thống chat', 'Thành công');
      this.load();
      this.webSocketService.init();
      this.webSocketService.listen('conversations').subscribe((data) => {
        let type = data['type'];
        if (type == 'update_message') {
          let conversation = data['data']['conversation'];
          let isNew = true;
          let message = data['data']['message'];
          for (let i = 0; i < this.list.length; i++) {
            let c = this.list[i];
            if (c.id == conversation['id']) {
              this.list.splice(i, 1);
              this.list.unshift(conversation);
              this.listNew = conversation['id'];
              isNew = false;
              break;
            }
          }
          if (isNew) {
            this.list.unshift(conversation);
          }
          if (this.conversationSelected && this.conversationSelected.id == conversation['id']) {
            this.newMessage = message;
            if (this.listNew == this.status) {
              this.listNew = 0;
            }
          }
        }
       })
    }, () => this.service.toastFailed('Truy cập hệ thống chat', 'Thất bại'));
  }

  onDestroy(): void {
    this.webSocketService.disconnect();
  }

  getTitle(): string {
    return 'Hệ thống chat';
  }

  getCreateModalComponent(): any {
    return null;
  }

  getEditModalComponent(): any {
    return null;
  }

  getCreateModalComponentOptions(): ModalOptions {
    return null;
  }

  getEditModalComponentOptions(): ModalOptions {
    return null;
  }

  buildSearchForm(): FormGroup {
    return this.formBuilder.group({
      search: new FormControl(null),
    });
  }

  initSearchForm(): FieldForm[] {
    return [
      FieldForm.createTextInput('Tìm kiếm', 'search', 'Nhập từ khóa')
    ];
  }

  initNewModel(): ConversationMeta {
    return new ConversationMeta();
  }

  constructor(
    service: ConversationService,
    modal: BsModalService,
    title: TitleService,
    builder: FormBuilder,
    private agentService: AgentService,
    private webSocketService: WebSocketService
  ) {
    super(service, modal, builder,);
  }

  clickDetailConversion(item: ConversationMeta, i: number) {
    this.conversationSelected = item;
    this.status = item.id;
    if (this.listNew == this.status) {
      this.listNew = 0;
    }
  }

  conversationChangedHandler(conversationChanged: any) {
    this.conversationChanged = conversationChanged;
    for (let i = 0; i < this.list.length; i++) {
      let c = this.list[i];
      if (c.id == this.conversationChanged['id']) {
        this.list.splice(i, 1);
        this.list.unshift(this.conversationChanged);
        this.status = this.conversationChanged['id'];
        break;
      }
    }
  }

  loadMore() {
    let time = this.list[this.list.length - 1].update_time;
    this.service.loadByParams({ update_time: time }).subscribe(res => {
      if (res['data'].length > 0) {
        for (let i of res['data']) {
          this.list.push(i);
        }
      }
    });
  }
}
